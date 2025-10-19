<?php

namespace App\Livewire\Referee;

use App\Models\ActOfWork;
use App\Models\Operation;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Мои АВР')]
class MyActOfWorks extends Component
{
    public $activeTab = 'pending';

    // Modal states
    public $showViewModal = false;
    public $showConfirmationModal = false;
    public $selectedAvr = null;

    // Confirmation form
    public $judge_comment = '';
    public $confirmationAction = null; // 'accept' or 'reject'

    public function mount()
    {
        $this->authorize('view-own-avr');
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->reset(['showViewModal', 'showConfirmationModal', 'selectedAvr', 'judge_comment', 'confirmationAction']);
    }

    public function openViewModal($avrId)
    {
        $this->selectedAvr = ActOfWork::with([
            'match.ownerClub',
            'match.guestClub',
            'match.stadium',
            'match.league',
            'match.season',
            'user',
            'operation',
            'act_of_work_services.common_service'
        ])->findOrFail($avrId);

        $this->showViewModal = true;
    }

    public function closeViewModal()
    {
        $this->showViewModal = false;
        $this->selectedAvr = null;
    }

    public function openConfirmationModal($avrId, $action)
    {
        $this->selectedAvr = ActOfWork::with([
            'match.ownerClub',
            'match.guestClub',
            'match.stadium',
            'match.league',
            'match.season',
            'user',
            'operation',
            'act_of_work_services.common_service'
        ])->findOrFail($avrId);

        $this->confirmationAction = $action;
        $this->judge_comment = $this->selectedAvr->judge_comment ?? '';
        $this->showConfirmationModal = true;
    }

    public function closeConfirmationModal()
    {
        $this->showConfirmationModal = false;
        $this->selectedAvr = null;
        $this->judge_comment = '';
        $this->confirmationAction = null;
    }

    public function confirmAction()
    {
        if (!$this->selectedAvr) return;

        if ($this->confirmationAction === 'accept') {
            $this->acceptAvr();
        } elseif ($this->confirmationAction === 'reject') {
            $this->rejectAvr();
        }
    }

    protected function acceptAvr()
    {
        // Update judge_status to 1 and move to next operation
        $approvalOperation = Operation::where('value', 'avr_approval_by_committee')->firstOrFail();

        $this->selectedAvr->update([
            'judge_status' => 1,
            'judge_comment' => $this->judge_comment,
            'operation_id' => $approvalOperation->id,
        ]);

        session()->flash('message', 'АВР успешно принят');
        $this->closeConfirmationModal();
    }

    protected function rejectAvr()
    {
        $this->validate([
            'judge_comment' => 'required|string|min:10',
        ], [
            'judge_comment.required' => 'Укажите причину отказа',
            'judge_comment.min' => 'Причина должна содержать минимум 10 символов',
        ]);

        // Update judge_status to -1 and move to reprocessing operation
        $reprocessingOperation = Operation::where('value', 'avr_reprocessing')->firstOrFail();

        $this->selectedAvr->update([
            'judge_status' => -1,
            'judge_comment' => $this->judge_comment,
            'operation_id' => $reprocessingOperation->id,
        ]);

        session()->flash('message', 'АВР отклонен и отправлен на переоформление');
        $this->closeConfirmationModal();
    }

    public function render()
    {
        // Tab 1: Ожидают ответа (Pending Response)
        $refereeConfirmationOp = Operation::where('value', 'referee_confirmation')->first();

        $pendingAvrs = ActOfWork::with([
                'match.ownerClub',
                'match.guestClub',
                'match.stadium',
                'match.league',
                'match.season',
                'user',
                'operation',
                'act_of_work_services.common_service'
            ])
            ->where('judge_id', auth()->id())
            ->where('operation_id', $refereeConfirmationOp?->id)
            ->where('judge_status', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        // Tab 2: Все мои АВР (All My AVRs)
        $allAvrs = ActOfWork::with([
                'match.ownerClub',
                'match.guestClub',
                'match.stadium',
                'match.league',
                'match.season',
                'user',
                'operation',
                'act_of_work_services.common_service'
            ])
            ->where('judge_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.referee.my-act-of-works', [
            'pendingAvrs' => $pendingAvrs,
            'allAvrs' => $allAvrs,
        ])->layout(get_user_layout());
    }
}
