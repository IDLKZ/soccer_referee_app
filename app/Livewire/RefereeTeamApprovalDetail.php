<?php

namespace App\Livewire;

use App\Models\MatchEntity;
use App\Models\MatchJudge;
use App\Models\Operation;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;

#[Title('Детали утверждения бригады')]
class RefereeTeamApprovalDetail extends Component
{
    public $matchId;
    public $match;
    public $ownerClub;
    public $guestClub;

    // Текущая активная вкладка
    public $activeTab = 'waiting'; // waiting, approved, rejected

    // Модальные окна
    public $showApproveModal = false;
    public $showRejectModal = false;
    public $showJudgeInfoModal = false;
    public $selectedJudgeId = null;
    public $selectedJudge = null;

    #[Validate('required|string|min:10')]
    public $rejectReason = '';

    // Статистика по вкладкам
    public $waitingCount = 0;
    public $approvedCount = 0;
    public $rejectedCount = 0;

    public function mount($id)
    {
        $this->authorize('approve-referee-team');

        $this->matchId = $id;
        $this->loadMatch();
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
            'match_judges' => function($q) {
                // Только те, кто согласился (judge_response == 1)
                $q->where('judge_response', 1);
            },
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
        $judges = $this->match->match_judges->where('judge_response', 1);

        $this->waitingCount = $judges->where('final_status', 0)->count();
        $this->approvedCount = $judges->where('final_status', 1)->count();
        $this->rejectedCount = $judges->where('final_status', -1)->count();
    }

    /**
     * Переключение вкладок
     */
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    /**
     * Открыть модальное окно информации о судье
     */
    public function openJudgeInfo($judgeId)
    {
        $this->selectedJudgeId = $judgeId;
        $this->selectedJudge = MatchJudge::with(['user', 'judge_type'])
            ->findOrFail($judgeId);
        $this->showJudgeInfoModal = true;
    }

    /**
     * Открыть модальное окно утверждения
     */
    public function openApproveModal($judgeId)
    {
        $this->selectedJudgeId = $judgeId;
        $this->showApproveModal = true;
    }

    /**
     * Открыть модальное окно отказа
     */
    public function openRejectModal($judgeId)
    {
        $this->selectedJudgeId = $judgeId;
        $this->rejectReason = '';
        $this->showRejectModal = true;
    }

    /**
     * Закрыть модальные окна
     */
    public function closeModals()
    {
        $this->showApproveModal = false;
        $this->showRejectModal = false;
        $this->showJudgeInfoModal = false;
        $this->selectedJudgeId = null;
        $this->selectedJudge = null;
        $this->rejectReason = '';
        $this->resetValidation();
    }

    /**
     * Утвердить судью
     */
    public function approveJudge()
    {
        $judge = MatchJudge::findOrFail($this->selectedJudgeId);

        // Проверка, что судья согласился
        if ($judge->judge_response !== 1) {
            session()->flash('error', 'Можно утверждать только судей, которые дали согласие');
            $this->closeModals();
            return;
        }

        // Утверждение
        $judge->update([
            'final_status' => 1,
        ]);

        session()->flash('message', 'Судья успешно утвержден');
        $this->loadMatch();
        $this->closeModals();
    }

    /**
     * Отклонить судью
     */
    public function rejectJudge()
    {
        $this->validate([
            'rejectReason' => 'required|string|min:10',
        ], [
            'rejectReason.required' => 'Укажите причину отказа',
            'rejectReason.min' => 'Причина должна содержать минимум 10 символов',
        ]);

        $judge = MatchJudge::findOrFail($this->selectedJudgeId);

        // Проверка, что судья согласился
        if ($judge->judge_response !== 1) {
            session()->flash('error', 'Можно отклонять только судей, которые дали согласие');
            $this->closeModals();
            return;
        }

        // Отклонение
        $judge->update([
            'final_status' => -1,
            'cancel_reason' => $this->rejectReason,
        ]);

        session()->flash('message', 'Судья отклонен');
        $this->loadMatch();
        $this->closeModals();
    }

    /**
     * Проверка: есть ли хотя бы один отклоненный обязательный судья
     */
    public function hasRejectedRequiredJudge()
    {
        foreach ($this->match->judge_requirements as $requirement) {
            if ($requirement->is_required) {
                $rejectedCount = MatchJudge::where('match_id', $this->matchId)
                    ->where('type_id', $requirement->judge_type_id)
                    ->where('judge_response', 1)
                    ->where('final_status', -1)
                    ->count();

                if ($rejectedCount > 0) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Проверка: все ли обязательные судьи утверждены
     */
    public function allRequiredJudgesApproved()
    {
        foreach ($this->match->judge_requirements as $requirement) {
            if ($requirement->is_required) {
                $approvedCount = MatchJudge::where('match_id', $this->matchId)
                    ->where('type_id', $requirement->judge_type_id)
                    ->where('judge_response', 1)
                    ->where('final_status', 1)
                    ->count();

                if ($approvedCount < $requirement->qty) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Отправить на доработку (если есть отклоненные обязательные судьи)
     */
    public function sendForRevision()
    {
        if (!$this->hasRejectedRequiredJudge()) {
            session()->flash('error', 'Нет отклоненных обязательных судей');
            return;
        }

        try {
            $reassignmentOperation = Operation::where('value', 'referee_reassignment')->first();

            if (!$reassignmentOperation) {
                throw new \Exception('Операция referee_reassignment не найдена');
            }

            $this->match->update([
                'current_operation_id' => $reassignmentOperation->id,
            ]);

            session()->flash('message', 'Матч отправлен на доработку');
            return redirect()->route('referee-team-approval-cards');

        } catch (\Exception $e) {
            session()->flash('error', 'Ошибка при отправке: ' . $e->getMessage());
        }
    }

    /**
     * Отправить на выбор логистов или транспорта
     */
    public function sendToNextStage()
    {
        if (!$this->allRequiredJudgesApproved()) {
            session()->flash('error', 'Не все обязательные судьи утверждены');
            return;
        }

        try {
            // Проверяем количество логистов
            $logistsCount = \App\Models\MatchLogist::where('match_id', $this->matchId)->count();

            if ($logistsCount == 0) {
                // Отправляем на выбор логистов
                $operation = Operation::where('value', 'select_responsible_logisticians')->first();
            } else {
                // Отправляем на выбор транспорта
                $operation = Operation::where('value', 'select_transport_departure')->first();
            }

            if (!$operation) {
                throw new \Exception('Операция не найдена');
            }

            $this->match->update([
                'current_operation_id' => $operation->id,
            ]);

            session()->flash('message', 'Матч отправлен на следующий этап');
            return redirect()->route('referee-team-approval-cards');

        } catch (\Exception $e) {
            session()->flash('error', 'Ошибка при отправке: ' . $e->getMessage());
        }
    }

    /**
     * Возврат на страницу карточек
     */
    public function goBack()
    {
        return redirect()->route('referee-team-approval-cards');
    }

    /**
     * Получение судей для текущей вкладки
     */
    public function getJudgesForTab()
    {
        $judges = $this->match->match_judges->where('judge_response', 1);

        switch ($this->activeTab) {
            case 'waiting':
                return $judges->where('final_status', 0);

            case 'approved':
                return $judges->where('final_status', 1);

            case 'rejected':
                return $judges->where('final_status', -1);

            default:
                return collect([]);
        }
    }

    public function render()
    {
        return view('livewire.referee-team-approval-detail', [
            'judgesForTab' => $this->getJudgesForTab(),
        ])->layout(get_user_layout());
    }
}
