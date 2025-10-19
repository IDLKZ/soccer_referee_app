<?php

namespace App\Livewire\FinanceDepartmentSpecialist;

use App\Models\ActOfWork;
use App\Models\Operation;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Первичная финансовая проверка АВР')]
class PrimaryFinancialCheck extends Component
{
    // Modal states
    public $showViewModal = false;
    public $showConfirmationModal = false;
    public $selectedAvr = null;

    // Confirmation form
    public $first_financial_comment = '';
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
        $this->first_financial_comment = $this->selectedAvr->first_financial_comment ?? '';
        $this->showConfirmationModal = true;
    }

    public function closeConfirmationModal()
    {
        $this->showConfirmationModal = false;
        $this->selectedAvr = null;
        $this->first_financial_comment = '';
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
        // Update first_financial_status to 1 and move to control financial check
        $controlFinancialCheckOperation = Operation::where('value', 'control_financial_check')->firstOrFail();

        $this->selectedAvr->update([
            'first_financial_status' => 1,
            'first_financial_comment' => $this->first_financial_comment,
            'operation_id' => $controlFinancialCheckOperation->id,
        ]);

        session()->flash('message', 'АВР успешно принят и отправлен на контрольную финансовую проверку');
        $this->closeConfirmationModal();
    }

    protected function rejectAvr()
    {
        $this->validate([
            'first_financial_comment' => 'required|string|min:10',
        ], [
            'first_financial_comment.required' => 'Укажите причину отказа',
            'first_financial_comment.min' => 'Причина должна содержать минимум 10 символов',
        ]);

        // Update first_financial_status to -1 and move to reprocessing operation
        $reprocessingOperation = Operation::where('value', 'avr_reprocessing')->firstOrFail();

        $this->selectedAvr->update([
            'first_financial_status' => -1,
            'first_financial_comment' => $this->first_financial_comment,
            'operation_id' => $reprocessingOperation->id,
        ]);

        session()->flash('message', 'АВР отклонен и отправлен на переоформление');
        $this->closeConfirmationModal();
    }

    public function render()
    {
        // Get AVRs waiting for primary financial check
        $primaryFinancialCheckOp = Operation::where('value', 'primary_financial_check')->first();

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
            ->where('operation_id', $primaryFinancialCheckOp?->id)
            ->where('first_financial_status', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.finance-department-specialist.primary-financial-check', [
            'pendingAvrs' => $pendingAvrs,
        ])->layout(get_user_layout());
    }
}
