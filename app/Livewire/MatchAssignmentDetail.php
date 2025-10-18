<?php

namespace App\Livewire;

use App\Models\MatchEntity;
use App\Models\MatchJudge;
use App\Models\User;
use App\Models\JudgeType;
use App\Constants\RoleConstants;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;

#[Title('Детали назначения судей')]
class MatchAssignmentDetail extends Component
{
    public $matchId;
    public $match;
    public $ownerClub;
    public $guestClub;

    // Текущая активная вкладка
    public $activeTab = 'approved';

    // Модальное окно отправки приглашения
    public $showInvitationModal = false;
    public $selectedJudgeTypeId = '';
    public $selectedJudgeId = '';

    // Доступные судьи и типы для приглашения
    public $availableJudges = [];
    public $judgeTypes = [];

    // Статистика по вкладкам
    public $approvedCount = 0;
    public $waitingResponseCount = 0;
    public $waitingDirectorCount = 0;
    public $rejectedCount = 0;

    public function mount($id)
    {
        $this->authorize('assign-referees');

        $this->matchId = $id;

        // Загрузка матча со всеми связями
        $this->loadMatch();

        // Загрузка типов судей
        $this->judgeTypes = JudgeType::all();
    }

    /**
     * Загрузка данных матча
     */
    public function loadMatch()
    {
        $this->match = MatchEntity::with([
            'league',
            'season',
            'stadium.city',
            'city',
            'operation',
            'ownerClub',
            'guestClub',
            'judge_requirements.judge_type',
            'match_judges.user',
            'match_judges.judge_type',
        ])->findOrFail($this->matchId);

        // Загрузка клубов через relationships
        $this->ownerClub = $this->match->ownerClub;
        $this->guestClub = $this->match->guestClub;

        // Подсчет статистики
        $this->calculateStatistics();
    }

    /**
     * Подсчет статистики по вкладкам
     */
    public function calculateStatistics()
    {
        $judges = $this->match->match_judges;

        // Утверждено: judge_response == 1 и final_status == 1
        $this->approvedCount = $judges->where('judge_response', 1)->where('final_status', 1)->count();

        // Ожидание ответа: judge_response == 0
        $this->waitingResponseCount = $judges->where('judge_response', 0)->count();

        // Ожидание директора: judge_response == 1 и final_status == 0
        $this->waitingDirectorCount = $judges->where('judge_response', 1)->where('final_status', 0)->count();

        // Отказано: judge_response == -1 или final_status == -1
        $this->rejectedCount = $judges->filter(function($judge) {
            return $judge->judge_response == -1 || $judge->final_status == -1;
        })->count();
    }

    /**
     * Переключение вкладок
     */
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    /**
     * Открытие модального окна приглашения
     */
    public function openInvitationModal()
    {
        $this->showInvitationModal = true;
        $this->selectedJudgeTypeId = '';
        $this->selectedJudgeId = '';
        $this->availableJudges = [];
    }

    /**
     * Закрытие модального окна
     */
    public function closeInvitationModal()
    {
        $this->showInvitationModal = false;
        $this->reset(['selectedJudgeTypeId', 'selectedJudgeId', 'availableJudges']);
    }

    /**
     * Загрузка доступных судей при выборе типа
     */
    public function updatedSelectedJudgeTypeId($value)
    {
        if ($value) {
            // Собираем ID городов, из которых судьи не должны быть
            $excludedCityIds = [];

            // Город стадиона
            if ($this->match->stadium && $this->match->stadium->city_id) {
                $excludedCityIds[] = $this->match->stadium->city_id;
            }

            // Город матча (если указан напрямую)
            if ($this->match->city_id) {
                $excludedCityIds[] = $this->match->city_id;
            }

            // Город клуба-хозяина
            if ($this->ownerClub && $this->ownerClub->city_id) {
                $excludedCityIds[] = $this->ownerClub->city_id;
            }

            // Город клуба-гостя
            if ($this->guestClub && $this->guestClub->city_id) {
                $excludedCityIds[] = $this->guestClub->city_id;
            }

            // Удаляем дубликаты
            $excludedCityIds = array_unique($excludedCityIds);

            // Загрузка пользователей с ролью SOCCER_REFEREE
            $this->availableJudges = User::whereHas('role', function($query) {
                    $query->where('value', RoleConstants::SOCCER_REFEREE);
                })
                ->where('is_active', true)
                // Исключаем судей, которые уже приглашены на ЛЮБУЮ позицию в этом матче
                ->whereNotIn('id', function($query) {
                    $query->select('judge_id')
                          ->from('match_judges')
                          ->where('match_id', $this->matchId)
                          ->where('judge_response', '!=', -1); // Не учитываем отказавшихся
                })
                // Исключаем судей из городов матча и клубов
                ->when(count($excludedCityIds) > 0, function($query) use ($excludedCityIds) {
                    $query->whereNotIn('id', function($subQuery) use ($excludedCityIds) {
                        $subQuery->select('user_id')
                                 ->from('judge_cities')
                                 ->whereIn('city_id', $excludedCityIds);
                    });
                })
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->get();
        } else {
            $this->availableJudges = [];
        }
    }

    /**
     * Отправка приглашения судье
     */
    public function sendInvitation()
    {
        // Валидация
        $this->validate([
            'selectedJudgeTypeId' => 'required|exists:judge_types,id',
            'selectedJudgeId' => 'required|exists:users,id',
        ], [
            'selectedJudgeTypeId.required' => 'Выберите тип судьи',
            'selectedJudgeId.required' => 'Выберите судью',
        ]);

        // Проверка: судья не должен быть уже приглашен на ЛЮБУЮ позицию в этом матче
        $existingInvitation = MatchJudge::where('match_id', $this->matchId)
            ->where('judge_id', $this->selectedJudgeId)
            ->where('judge_response', '!=', -1) // Не считаем отказавшихся
            ->first();

        if ($existingInvitation) {
            $this->addError('selectedJudgeId', 'Этот судья уже приглашен на другую позицию в данном матче');
            return;
        }

        // Проверка: судья не должен быть из города матча или клубов
        $excludedCityIds = [];

        if ($this->match->stadium && $this->match->stadium->city_id) {
            $excludedCityIds[] = $this->match->stadium->city_id;
        }
        if ($this->match->city_id) {
            $excludedCityIds[] = $this->match->city_id;
        }
        if ($this->ownerClub && $this->ownerClub->city_id) {
            $excludedCityIds[] = $this->ownerClub->city_id;
        }
        if ($this->guestClub && $this->guestClub->city_id) {
            $excludedCityIds[] = $this->guestClub->city_id;
        }

        $excludedCityIds = array_unique($excludedCityIds);

        if (count($excludedCityIds) > 0) {
            $judgeHasConflictCity = \App\Models\JudgeCity::where('user_id', $this->selectedJudgeId)
                ->whereIn('city_id', $excludedCityIds)
                ->exists();

            if ($judgeHasConflictCity) {
                $this->addError('selectedJudgeId', 'Этот судья привязан к городу проведения матча или клубов');
                return;
            }
        }

        // Проверка: не превышает ли количество приглашений требования
        $requirement = $this->match->judge_requirements->where('judge_type_id', $this->selectedJudgeTypeId)->first();

        if (!$requirement) {
            $this->addError('selectedJudgeTypeId', 'Для этого типа судьи нет требований в матче');
            return;
        }

        // Подсчет текущих приглашений этого типа (не отказанных)
        $currentInvitations = MatchJudge::where('match_id', $this->matchId)
            ->where('type_id', $this->selectedJudgeTypeId)
            ->where('judge_response', '!=', -1)
            ->where('final_status', '!=', -1)
            ->count();

        if ($currentInvitations >= $requirement->qty) {
            $this->addError('selectedJudgeTypeId', 'Достигнуто максимальное количество приглашений для этого типа судьи');
            return;
        }

        // Создание приглашения
        try {
            MatchJudge::create([
                'match_id' => $this->matchId,
                'judge_id' => $this->selectedJudgeId,
                'type_id' => $this->selectedJudgeTypeId,
                'judge_response' => 0, // Ожидание ответа
                'final_status' => 0,   // Ожидание финального утверждения
                'cancel_reason' => null,
            ]);

            // Обновление данных
            $this->loadMatch();

            // Закрытие модального окна
            $this->closeInvitationModal();

            // Уведомление об успехе
            session()->flash('message', 'Приглашение успешно отправлено');

        } catch (\Exception $e) {
            $this->addError('general', 'Ошибка при отправке приглашения: ' . $e->getMessage());
        }
    }

    /**
     * Проверка: можно ли отправлять приглашения
     * Условия: operation_value in ['match_created_waiting_referees', 'referee_reassignment']
     * И не достигнуто количество по всем типам судей
     */
    public function canSendInvitations()
    {
        $operationValue = $this->match->operation->value;

        return in_array($operationValue, ['match_created_waiting_referees', 'referee_reassignment']);
    }

    /**
     * Проверка: можно ли отправить на рассмотрение директору
     * Условия:
     * 1. Статус матча: match_created_waiting_referees или referee_reassignment
     * 2. По каждому обязательному типу (is_required == true) количество утвержденных судей (judge_response == 1 и final_status == 0) == qty
     */
    public function canSubmitToDirector()
    {
        $operationValue = $this->match->operation->value;

        if (!in_array($operationValue, ['match_created_waiting_referees', 'referee_reassignment'])) {
            return false;
        }

        // Проверка по каждому обязательному требованию
        foreach ($this->match->judge_requirements as $requirement) {
            if ($requirement->is_required) {
                // Подсчет судей с ответом "согласен" но еще не утвержденных директором
                $count = MatchJudge::where('match_id', $this->matchId)
                    ->where('type_id', $requirement->judge_type_id)
                    ->where('judge_response', 1)
                    ->where('final_status', 0)
                    ->count();

                if ($count < $requirement->qty) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Отправка на рассмотрение директору
     * Меняет статус матча на referee_team_approval
     */
    public function submitToDirector()
    {
        if (!$this->canSubmitToDirector()) {
            session()->flash('error', 'Не выполнены условия для отправки на рассмотрение директору');
            return;
        }

        try {
            // Получение операции referee_team_approval
            $approvalOperation = \App\Models\Operation::where('value', 'referee_team_approval')->first();

            if (!$approvalOperation) {
                throw new \Exception('Операция referee_team_approval не найдена');
            }

            // Обновление статуса матча
            $this->match->update([
                'current_operation_id' => $approvalOperation->id,
            ]);

            // Обновление данных
            $this->loadMatch();

            session()->flash('message', 'Матч отправлен на рассмотрение директору департамента');

        } catch (\Exception $e) {
            session()->flash('error', 'Ошибка при отправке: ' . $e->getMessage());
        }
    }

    /**
     * Возврат на страницу карточек
     */
    public function goBack()
    {
        return redirect()->route('match-assignment-cards');
    }

    /**
     * Получение судей для текущей вкладки
     */
    public function getJudgesForTab()
    {
        $judges = $this->match->match_judges;

        switch ($this->activeTab) {
            case 'approved':
                // Утверждено: judge_response == 1 и final_status == 1
                return $judges->where('judge_response', 1)->where('final_status', 1);

            case 'waiting_response':
                // Ожидание ответа: judge_response == 0
                return $judges->where('judge_response', 0);

            case 'waiting_director':
                // Ожидание директора: judge_response == 1 и final_status == 0
                return $judges->where('judge_response', 1)->where('final_status', 0);

            case 'rejected':
                // Отказано: judge_response == -1 или final_status == -1
                return $judges->filter(function($judge) {
                    return $judge->judge_response == -1 || $judge->final_status == -1;
                });

            default:
                return collect([]);
        }
    }

    public function render()
    {
        return view('livewire.match-assignment-detail', [
            'judgesForTab' => $this->getJudgesForTab(),
        ])->layout(get_user_layout());
    }
}
