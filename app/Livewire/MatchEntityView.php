<?php

namespace App\Livewire;

use App\Models\MatchEntity;
use App\Models\Club;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Просмотр матча')]
class MatchEntityView extends Component
{
    public $matchId;
    public $match;
    public $ownerClub;
    public $guestClub;

    public function mount($id)
    {
        $this->authorize('view-matches');

        $this->matchId = $id;

        $this->match = MatchEntity::with([
            'league',
            'season',
            'stadium.city',
            'city',
            'operation',
            'club',
            'judge_requirements.judge_type',
            'match_deadlines.operation',
            'match_logists.user',
            'match_judges.user',
            'match_judges.judge_type',
            'protocols',
            'trips.user',
        ])->findOrFail($id);

        // Загружаем клубы отдельно для полной информации
        $this->ownerClub = Club::find($this->match->owner_club_id);
        $this->guestClub = Club::find($this->match->guest_club_id);
    }

    public function goBack()
    {
        return redirect()->route('match-entity-management');
    }

    public function render()
    {
        return view('livewire.match-entity-view')->layout(get_user_layout());
    }
}
