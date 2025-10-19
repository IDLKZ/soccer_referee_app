<?php

namespace App\Livewire;

use App\Constants\RoleConstants;
use App\Models\Trip;
use App\Models\ActOfWork;
use App\Models\ActOfPayment;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;

#[Title('Финансовое управление')]
class FinanceManagement extends Component
{
    public $trips = [];
    public $workActs = [];
    public $payments = [];
    public $activeTab = 'trips';

    #[Locked]
    public $canApprovePayments = false;

    #[Locked]
    public $canViewReports = false;

    #[Locked]
    public $canCreateWorkActs = false;

    public function mount()
    {
        $this->authorize('manage-finance');

        $user = auth()->user();
        $this->canApprovePayments = $user->can('approve-payments');
        $this->canViewReports = $user->can('view-reports');
        $this->canCreateWorkActs = in_array($user->role->value, [
            RoleConstants::ADMINISTRATOR,
            RoleConstants::REFEREEING_DEPARTMENT_HEAD,
            RoleConstants::REFEREEING_DEPARTMENT_EMPLOYEE,
            RoleConstants::REFEREEING_DEPARTMENT_ACCOUNTANT,
        ]);

        $this->loadData();
    }

    public function loadData()
    {
        $user = auth()->user();

        if ($user->role->value === RoleConstants::SOCCER_REFEREE) {
            // Судьи видят только свои данные
            $this->trips = Trip::whereHas('matchJudges', function($query) use ($user) {
                $query->where('judge_id', $user->id);
            })->with(['match', 'city'])->get();

            $this->workActs = ActOfWork::where('judge_id', $user->id)->with(['match', 'trip'])->get();
        } else {
            // Финансовые сотрудники видят все данные
            $this->trips = Trip::with(['match', 'city'])->get();
            $this->workActs = ActOfWork::with(['match', 'trip', 'user'])->get();
        }

        $this->payments = ActOfPayment::with(['act_of_work.match', 'user'])->get();
    }

    public function approvePayment($paymentId)
    {
        $this->authorize('approve-payments');

        $payment = ActOfPayment::findOrFail($paymentId);
        $payment->checked_by = auth()->id();
        $payment->checked_at = now();
        $payment->status = 'approved';
        $payment->save();

        $this->loadData();
        session()->flash('message', 'Платеж одобрен');
    }

    public function rejectPayment($paymentId)
    {
        $this->authorize('approve-payments');

        $payment = ActOfPayment::findOrFail($paymentId);
        $payment->checked_by = auth()->id();
        $payment->checked_at = now();
        $payment->status = 'rejected';
        $payment->save();

        $this->loadData();
        session()->flash('message', 'Платеж отклонен');
    }

    public function render()
    {
        return view('livewire.finance-management')
            ->layout('layouts.app');
    }
}