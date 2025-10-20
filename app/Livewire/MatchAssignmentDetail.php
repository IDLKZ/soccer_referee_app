<?php

namespace App\Livewire;

use App\Models\JudgeRequirement;
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
                'operation_id' => 1
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
        $all_field = true;
        $operationValue = $this->match->operation->value;
        $required_judge_requirements_qty = JudgeRequirement::where("match_id", $this->matchId)->pluck('qty', 'judge_type_id')->toArray();
        $nowadays_count = MatchJudge::where('match_id', $this->matchId)
            ->whereIn('judge_response', [0, 1])
            ->whereIn('final_status', [0, 1])
            ->selectRaw('type_id, COUNT(*) as total_qty')
            ->groupBy('type_id')
            ->pluck('total_qty', 'type_id')
            ->toArray();
        foreach ($required_judge_requirements_qty as $type_id => $judge_qty) {
            if(key_exists($type_id, $nowadays_count)) {
                if ($nowadays_count[$type_id] == $judge_qty) {
                    $all_field = false;
                }
                else{
                    $all_field = true;
                    break;
                }
            }
            else {
                $all_field = true;
                break;
            }
        }
        $check_in_status =  in_array($operationValue, ['match_created_waiting_referees', 'referee_reassignment',"referee_team_approval"]);
        return $check_in_status && $all_field;
    }

    /**
     * Проверка: можно ли отправить конкретную заявку судьи директору
     * Условия:
     * 1. judge_response == 1 (судья согласился)
     * 2. final_status == 0 (еще не утвержден директором)
     * 3. operation_id == 1 (или NULL - статус после согласия судьи)
     */
    public function canSubmitToDirector($matchJudgeId)
    {
        $matchJudge = MatchJudge::find($matchJudgeId);

        if (!$matchJudge) {
            return false;
        }

        return $matchJudge->judge_response == 1
            && $matchJudge->final_status == 0
            && ($matchJudge->operation_id == 1 || $matchJudge->operation_id === null);
    }

    /**
     * Отправка конкретной заявки судьи на рассмотрение директору
     * Обновляет operation_id заявки на 2
     * Проверяет, все ли заявки отправлены, и обновляет статус матча
     */
    public function submitSingleToDirector($matchJudgeId)
    {
        if (!$this->canSubmitToDirector($matchJudgeId)) {
            session()->flash('error', 'Эта заявка не может быть отправлена директору');
            return;
        }

        try {
            $matchJudge = MatchJudge::findOrFail($matchJudgeId);

            // Обновляем operation_id на 2 (ожидание рассмотрения директором)
            $matchJudge->update([
                'operation_id' => 2
            ]);

            session()->flash('message', 'Заявка судьи отправлена на рассмотрение директору');

            // Проверяем, все ли требования выполнены, и обновляем статус матча
            $this->checkAndUpdateMatchOperation();

            // Обновление данных
            $this->loadMatch();

        } catch (\Exception $e) {
            session()->flash('error', 'Ошибка при отправке: ' . $e->getMessage());
        }
    }

    /**
     * Проверка всех заявок и обновление operation матча
     * Если все требования выполнены (по количеству judge_requirements):
     * - judge_response == 1
     * - final_status == 1 (утверждены директором)
     * - operation_id == 2
     * То переходим на следующий этап
     */
    protected function checkAndUpdateMatchOperation()
    {
        // Проверяем, все ли требования выполнены
        $allRequirementsMet = true;

        foreach ($this->match->judge_requirements as $requirement) {
            if ($requirement->is_required) {
                $count = MatchJudge::where('match_id', $this->matchId)
                    ->where('type_id', $requirement->judge_type_id)
                    ->where('judge_response', 1)
                    ->where('final_status', 1)
                    ->where('operation_id', 2)
                    ->count();

                if ($count < $requirement->qty) {
                    $allRequirementsMet = false;
                    break;
                }
            }
        }

        // Если все требования выполнены - переходим на следующий этап
        if ($allRequirementsMet) {
            $this->transitionToNextStage();
        } else {
            // Проверяем, все ли заявки имеют operation_id == 2 (отправлены директору)
            $allSubmittedToDirector = true;

            foreach ($this->match->judge_requirements as $requirement) {
                if ($requirement->is_required) {
                    $count = MatchJudge::where('match_id', $this->matchId)
                        ->where('type_id', $requirement->judge_type_id)
                        ->where('judge_response', 1)
                        ->where('final_status', 0)
                        ->where('operation_id', 2)
                        ->count();

                    if ($count < $requirement->qty) {
                        $allSubmittedToDirector = false;
                        break;
                    }
                }
            }

            // Если все отправлены директору - меняем статус матча на referee_team_approval
            if ($allSubmittedToDirector) {
                $approvalOperation = \App\Models\Operation::where('value', 'referee_team_approval')->first();
                if ($approvalOperation) {
                    $this->match->update([
                        'current_operation_id' => $approvalOperation->id
                    ]);
                }
            }
        }
    }

    /**
     * Переход на следующий этап после утверждения всех заявок директором
     * Проверяет наличие логистов и переходит либо на select_transport_departure,
     * либо на select_responsible_logisticians
     */
    protected function transitionToNextStage()
    {
        // Проверяем, есть ли у матча логисты
        $hasLogisticians = $this->match->match_logists()->exists();

        if ($hasLogisticians) {
            // Переходим на этап выбора транспорта
            $operation = \App\Models\Operation::where('value', 'select_transport_departure')->first();
        } else {
            // Переходим на этап выбора логистов
            $operation = \App\Models\Operation::where('value', 'select_responsible_logisticians')->first();
        }

        if ($operation) {
            $this->match->update([
                'current_operation_id' => $operation->id
            ]);
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
