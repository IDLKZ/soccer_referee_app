<?php

namespace App\Livewire\RefereeingDepartmentHead;

use App\Models\ActOfWork;
use App\Models\Operation;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Проверка АВР судейским комитетом')]
class AvrApprovalByCommittee extends Component
{
    // Modal states
    public $showViewModal = false;
    public $showConfirmationModal = false;
    public $selectedAvr = null;

    // Confirmation form
    public $control_comment = '';
    public $confirmationAction = null; // 'accept' or 'reject'

    public function mount()
    {
        $this->authorize('approve-referee-team');
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
        $this->control_comment = $this->selectedAvr->control_comment ?? '';
        $this->showConfirmationModal = true;
    }

    public function closeConfirmationModal()
    {
        $this->showConfirmationModal = false;
        $this->selectedAvr = null;
        $this->control_comment = '';
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
        // Update control_status to 1 and move to primary financial check
        $primaryFinancialCheckOperation = Operation::where('value', 'primary_financial_check')->firstOrFail();

        $this->selectedAvr->update([
            'control_status' => 1,
            'control_comment' => $this->control_comment,
            'operation_id' => $primaryFinancialCheckOperation->id,
        ]);

        session()->flash('message', 'АВР успешно принят и отправлен на первичную финансовую проверку');
        $this->closeConfirmationModal();
    }

    protected function rejectAvr()
    {
        $this->validate([
            'control_comment' => 'required|string|min:10',
        ], [
            'control_comment.required' => 'Укажите причину отказа',
            'control_comment.min' => 'Причина должна содержать минимум 10 символов',
        ]);

        // Update control_status to -1 and move to reprocessing operation
        $reprocessingOperation = Operation::where('value', 'avr_reprocessing')->firstOrFail();

        $this->selectedAvr->update([
            'control_status' => -1,
            'control_comment' => $this->control_comment,
            'operation_id' => $reprocessingOperation->id,
        ]);

        session()->flash('message', 'АВР отклонен и отправлен на переоформление');
        $this->closeConfirmationModal();
    }

    public function render()
    {
        // Get AVRs waiting for committee approval
        $committeeApprovalOp = Operation::where('value', 'avr_approval_by_committee')->first();

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
            ->where('operation_id', $committeeApprovalOp?->id)
            ->where('control_status', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.refereeing-department-head.avr-approval-by-committee', [
            'pendingAvrs' => $pendingAvrs,
        ])->layout(get_user_layout());
    }
}
