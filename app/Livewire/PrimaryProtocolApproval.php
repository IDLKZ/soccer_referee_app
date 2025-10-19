<?php

namespace App\Livewire;

use App\Models\Protocol;
use App\Models\Operation;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Первичная проверка протоколов')]
class PrimaryProtocolApproval extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedProtocol = null;
    public $showDecisionModal = false;
    public $decisionComment = '';

    protected $queryString = ['search' => ['except' => '']];

    public function mount()
    {
        $this->authorize('approve-primary-protocols');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openDecisionModal($protocolId)
    {
        $this->selectedProtocol = Protocol::with([
            'match.ownerClub',
            'match.guestClub',
            'match.stadium',
            'match.league',
            'match.season',
            'user',
            'requirement.type',
            'operation'
        ])->findOrFail($protocolId);

        $this->decisionComment = '';
        $this->showDecisionModal = true;
    }

    public function closeDecisionModal()
    {
        $this->showDecisionModal = false;
        $this->selectedProtocol = null;
        $this->decisionComment = '';
    }

    public function approve()
    {
        if (!$this->selectedProtocol) {
            return;
        }

        $this->validate([
            'decisionComment' => 'nullable|string|max:1000',
        ]);

        // Update protocol status
        $controlApprovalOperation = Operation::where('value', 'control_protocol_approval')->firstOrFail();

        $this->selectedProtocol->update([
            'first_status' => 1,
            'operation_id' => $controlApprovalOperation->id,
            'primary_comment' => $this->decisionComment,
        ]);

        session()->flash('message', 'Протокол успешно одобрен');
        $this->closeDecisionModal();
    }

    public function reject()
    {
        if (!$this->selectedProtocol) {
            return;
        }

        $this->validate([
            'decisionComment' => 'required|string|max:1000',
        ], [
            'decisionComment.required' => 'Комментарий обязателен при отказе',
        ]);

        // Update protocol status
        $reprocessingOperation = Operation::where('value', 'protocol_reprocessing')->firstOrFail();

        $this->selectedProtocol->update([
            'first_status' => -1,
            'operation_id' => $reprocessingOperation->id,
            'primary_comment' => $this->decisionComment,
        ]);

        session()->flash('message', 'Протокол отправлен на доработку');
        $this->closeDecisionModal();
    }

    public function render()
    {
        $primaryApprovalOperation = Operation::where('value', 'primary_protocol_approval')->first();

        $protocols = Protocol::query()
            ->with([
                'match.ownerClub',
                'match.guestClub',
                'match.stadium',
                'match.league',
                'match.season',
                'user',
                'requirement.type',
                'operation'
            ])
            ->where('operation_id', $primaryApprovalOperation?->id)
            ->where('first_status', 0)
            ->when($this->search, function($query) {
                $query->whereHas('match', function($q) {
                    $q->where('id', 'like', "%{$this->search}%")
                      ->orWhereHas('ownerClub', function($club) {
                          $club->where('name_ru', 'like', "%{$this->search}%")
                               ->orWhere('name_kk', 'like', "%{$this->search}%")
                               ->orWhere('name_en', 'like', "%{$this->search}%");
                      })
                      ->orWhereHas('guestClub', function($club) {
                          $club->where('name_ru', 'like', "%{$this->search}%")
                               ->orWhere('name_kk', 'like', "%{$this->search}%")
                               ->orWhere('name_en', 'like', "%{$this->search}%");
                      });
                })
                ->orWhereHas('user', function($q) {
                    $q->where('surname', 'like', "%{$this->search}%")
                      ->orWhere('name', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        return view('livewire.refereeing-department.primary-protocol-approval', [
            'protocols' => $protocols,
        ])->layout(get_user_layout());
    }
}
