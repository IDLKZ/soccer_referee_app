<?php

namespace App\Livewire;

use App\Constants\RoleConstants;
use App\Models\MatchEntity;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;

#[Title('Управление матчами')]
class MatchManagement extends Component
{
    public $matches = [];
    public $selectedMatch = null;

    #[Locked]
    public $canCreate = false;

    #[Locked]
    public $canEdit = false;

    #[Locked]
    public $canDelete = false;

    #[Locked]
    public $canAssignReferees = false;

    #[Locked]
    public $canManageLogistics = false;

    public function mount()
    {
        $this->authorize('view-matches');

        $user = auth()->user();
        $this->canCreate = $user->can('manage-matches');
        $this->canEdit = $user->can('manage-matches');
        $this->canDelete = $user->role->value === RoleConstants::ADMINISTRATOR ||
                          $user->role->value === RoleConstants::REFEREEING_DEPARTMENT_HEAD;
        $this->canAssignReferees = $user->can('assign-referees');
        $this->canManageLogistics = $user->can('manage-logistics');

        $this->loadMatches();
    }

    public function loadMatches()
    {
        $user = auth()->user();

        if ($user->role->value === RoleConstants::SOCCER_REFEREE) {
            // Судьи видят только свои матчи
            $this->matches = MatchEntity::whereHas('matchJudges', function($query) use ($user) {
                $query->where('judge_id', $user->id);
            })->with(['league', 'season', 'stadium', 'city'])->get();
        } else {
            // Остальные видят все матчи (согласно policy)
            $this->matches = MatchEntity::with(['league', 'season', 'stadium', 'city'])->get();
        }
    }

    public function selectMatch($matchId)
    {
        $match = MatchEntity::findOrFail($matchId);
        $this->authorize('view', $match);
        $this->selectedMatch = $match;
    }

    public function deleteMatch($matchId)
    {
        $match = MatchEntity::findOrFail($matchId);
        $this->authorize('delete', $match);

        $match->delete();
        $this->loadMatches();
        $this->selectedMatch = null;

        session()->flash('message', 'Матч успешно удален');
    }

    public function render()
    {
        return view('livewire.match-management')
            ->layout('layouts.app');
    }
}