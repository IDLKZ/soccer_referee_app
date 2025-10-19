<?php

namespace App\Livewire\FinanceDepartmentHead;

use App\Models\ActOfWork;
use App\Models\Operation;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Контрольная финансовая проверка АВР')]
class ControlFinancialCheck extends Component
{
    // Modal states
    public $showViewModal = false;
    public $showConfirmationModal = false;
    public $selectedAvr = null;

    // Confirmation form
    public $last_financial_comment = '';
    public $confirmationAction = null; // 'accept' or 'reject'

    public function mount()
    {
        $this->authorize('manage-finance');
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
        $this->last_financial_comment = $this->selectedAvr->last_financial_comment ?? '';
        $this->showConfirmationModal = true;
    }

    public function closeConfirmationModal()
    {
        $this->showConfirmationModal = false;
        $this->selectedAvr = null;
        $this->last_financial_comment = '';
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
        // Update last_financial_status to 1, final_status to 1 and move to confirmed waiting payment
        $confirmedWaitingPaymentOperation = Operation::where('value', 'avr_confirmed_waiting_payment')->firstOrFail();

        $this->selectedAvr->update([
            'last_financial_status' => 1,
            'final_status' => 1,
            'last_financial_comment' => $this->last_financial_comment,
            'operation_id' => $confirmedWaitingPaymentOperation->id,
        ]);

        session()->flash('message', 'АВР успешно принят и подтвержден. Ожидание оплаты');
        $this->closeConfirmationModal();
    }

    protected function rejectAvr()
    {
        $this->validate([
            'last_financial_comment' => 'required|string|min:10',
        ], [
            'last_financial_comment.required' => 'Укажите причину отказа',
            'last_financial_comment.min' => 'Причина должна содержать минимум 10 символов',
        ]);

        // Update last_financial_status to -1 and move to reprocessing operation
        $reprocessingOperation = Operation::where('value', 'avr_reprocessing')->firstOrFail();

        $this->selectedAvr->update([
            'last_financial_status' => -1,
            'last_financial_comment' => $this->last_financial_comment,
            'operation_id' => $reprocessingOperation->id,
        ]);

        session()->flash('message', 'АВР отклонен и отправлен на переоформление');
        $this->closeConfirmationModal();
    }

    public function render()
    {
        // Get AVRs waiting for control financial check
        $controlFinancialCheckOp = Operation::where('value', 'control_financial_check')->first();

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
            ->where('operation_id', $controlFinancialCheckOp?->id)
            ->where('last_financial_status', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.finance-department-head.control-financial-check', [
            'pendingAvrs' => $pendingAvrs,
        ])->layout(get_user_layout());
    }
}
