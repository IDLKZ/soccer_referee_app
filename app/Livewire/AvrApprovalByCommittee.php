<?php

namespace App\Livewire;

use App\Models\ActOfWork;
use App\Models\Operation;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Утверждение АВР судейским комитетом')]
class AvrApprovalByCommittee extends Component
{
    use WithPagination;

    public $search = '';
    public $showViewModal = false;
    public $selectedAvr = null;

    protected $queryString = ['search' => ['except' => '']];

    public function mount()
    {
        $this->authorize('avr-approval-by-committee');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Approve AVR
    public function approve($avrId)
    {
        $avr = ActOfWork::findOrFail($avrId);

        // Set control_status to approved
        $operation = Operation::where('value', 'primary_financial_check')->firstOrFail();
        $avr->update([
            'control_status' => 1,
            'operation_id' => $operation->id,
        ]);

        session()->flash('message', 'АВР успешно утвержден и отправлен на первичную финансовую проверку');
    }

    // Reject AVR
    public function reject($avrId)
    {
        $avr = ActOfWork::findOrFail($avrId);

        // Set control_status to rejected
        $operation = Operation::where('value', 'avr_reprocessing')->firstOrFail();
        $avr->update([
            'control_status' => -1,
            'operation_id' => $operation->id,
        ]);

        session()->flash('message', 'АВР отклонен и отправлен на переоформление');
    }

    // Open view modal
    public function openViewModal($avrId)
    {
        $this->selectedAvr = ActOfWork::with([
            'match.ownerClub', 'match.guestClub', 'match.stadium', 'match.league', 'match.season',
            'user', 'act_of_work_services.common_service', 'operation'
        ])->findOrFail($avrId);

        $this->showViewModal = true;
    }

    public function closeViewModal()
    {
        $this->showViewModal = false;
        $this->selectedAvr = null;
    }

    public function render()
    {
        // Get operation for committee approval
        $committeeApprovalOp = Operation::where('value', 'avr_approval_by_committee')->first();

        $avrs = ActOfWork::with(['match.ownerClub', 'match.guestClub', 'match.stadium', 'user', 'operation'])
            ->where('operation_id', $committeeApprovalOp?->id)
            ->where('control_status', 0)
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('act_number', 'like', "%{$this->search}%")
                      ->orWhereHas('match', function($matchQuery) {
                          $matchQuery->where('id', 'like', "%{$this->search}%");
                      })
                      ->orWhereHas('user', function($userQuery) {
                          $userQuery->where('last_name', 'like', "%{$this->search}%")
                                    ->orWhere('first_name', 'like', "%{$this->search}%");
                      });
                });
            })
            ->orderBy('created_at', 'asc')
            ->paginate(12);

        return view('livewire.avr-approval-by-committee', [
            'avrs' => $avrs,
        ])->layout(get_user_layout());
    }
}
