<?php

namespace App\Livewire;

use App\Models\MatchJudge;
use App\Models\MatchEntity;
use App\Models\Operation;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;

#[Title('Мои приглашения')]
class MyInvitationToBeReferee extends Component
{
    use WithPagination;

    // Фильтры
    public $filterStatus = 'all'; // all, waiting, accepted, rejected
    public $search = '';

    // Модальные окна
    public $showAcceptModal = false;
    public $showRejectModal = false;
    public $selectedInvitationId = null;

    #[Validate('required|string|min:10')]
    public $rejectReason = '';

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->authorize('view-own-invitations');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    /**
     * Получение приглашений для текущего судьи
     */
    public function getInvitations()
    {
        $query = MatchJudge::with([
            'match.league',
            'match.season',
            'match.stadium.city',
            'match.operation',
            'judge_type',
        ])
        ->where('judge_id', auth()->id());

        // Поиск
        if ($this->search) {
            $query->whereHas('match', function($q) {
                $q->whereHas('league', function($leagueQuery) {
                    $leagueQuery->where('title_ru', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('stadium', function($stadiumQuery) {
                    $stadiumQuery->where('title_ru', 'like', '%' . $this->search . '%');
                });
            });
        }

        // Фильтр по статусу
        switch ($this->filterStatus) {
            case 'waiting':
                $query->where('judge_response', 0);
                break;
            case 'accepted':
                $query->where('judge_response', 1);
                break;
            case 'rejected':
                $query->where('judge_response', -1);
                break;
        }

        return $query->orderBy('created_at', 'desc')->paginate(12);
    }

    /**
     * Открыть модальное окно принятия
     */
    public function openAcceptModal($invitationId)
    {
        $this->selectedInvitationId = $invitationId;
        $this->showAcceptModal = true;
    }

    /**
     * Открыть модальное окно отказа
     */
    public function openRejectModal($invitationId)
    {
        $this->selectedInvitationId = $invitationId;
        $this->rejectReason = '';
        $this->showRejectModal = true;
    }

    /**
     * Закрыть модальные окна
     */
    public function closeModals()
    {
        $this->showAcceptModal = false;
        $this->showRejectModal = false;
        $this->selectedInvitationId = null;
        $this->rejectReason = '';
        $this->resetValidation();
    }

    /**
     * Принять приглашение
     */
    public function acceptInvitation()
    {
        $invitation = MatchJudge::findOrFail($this->selectedInvitationId);

        // Проверка, что это приглашение для текущего судьи
        if ($invitation->judge_id !== auth()->id()) {
            session()->flash('error', 'У вас нет доступа к этому приглашению');
            $this->closeModals();
            return;
        }

        // Проверка, что приглашение в статусе ожидания
        if ($invitation->judge_response !== 0) {
            session()->flash('error', 'На это приглашение уже был дан ответ');
            $this->closeModals();
            return;
        }

        // Обновление статуса приглашения
        $invitation->update([
            'judge_response' => 1, // Согласен
        ]);

        session()->flash('message', 'Вы успешно приняли приглашение на матч');
        $this->closeModals();
    }

    /**
     * Отклонить приглашение
     */
    public function rejectInvitation()
    {
        $this->validate([
            'rejectReason' => 'required|string|min:10',
        ], [
            'rejectReason.required' => 'Укажите причину отказа',
            'rejectReason.min' => 'Причина должна содержать минимум 10 символов',
        ]);

        $invitation = MatchJudge::findOrFail($this->selectedInvitationId);

        // Проверка, что это приглашение для текущего судьи
        if ($invitation->judge_id !== auth()->id()) {
            session()->flash('error', 'У вас нет доступа к этому приглашению');
            $this->closeModals();
            return;
        }

        // Проверка, что приглашение в статусе ожидания
        if ($invitation->judge_response !== 0) {
            session()->flash('error', 'На это приглашение уже был дан ответ');
            $this->closeModals();
            return;
        }

        // Обновление статуса приглашения
        $invitation->update([
            'judge_response' => -1, // Отказ
            'cancel_reason' => $this->rejectReason,
        ]);

        // Изменение статуса матча на referee_reassignment
        $match = $invitation->match;
        $reassignmentOperation = Operation::where('value', 'referee_reassignment')->first();

        if ($reassignmentOperation) {
            $match->update([
                'current_operation_id' => $reassignmentOperation->id,
            ]);
        }

        session()->flash('message', 'Вы отклонили приглашение на матч');
        $this->closeModals();
    }

    /**
     * Получение статистики
     */
    public function getStatistics()
    {
        $userId = auth()->id();

        return [
            'total' => MatchJudge::where('judge_id', $userId)->count(),
            'waiting' => MatchJudge::where('judge_id', $userId)->where('judge_response', 0)->count(),
            'accepted' => MatchJudge::where('judge_id', $userId)->where('judge_response', 1)->count(),
            'rejected' => MatchJudge::where('judge_id', $userId)->where('judge_response', -1)->count(),
        ];
    }

    public function render()
    {
        return view('livewire.my-invitation-to-be-referee', [
            'invitations' => $this->getInvitations(),
            'statistics' => $this->getStatistics(),
        ])->layout(get_user_layout());
    }
}
