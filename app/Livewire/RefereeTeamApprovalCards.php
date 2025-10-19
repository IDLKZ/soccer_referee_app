<?php

namespace App\Livewire;

use App\Models\MatchEntity;
use App\Models\Operation;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Утверждение судейской бригады')]
class RefereeTeamApprovalCards extends Component
{
    use WithPagination;

    public $search = '';
    public $filterLeague = '';
    public $filterSeason = '';

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->authorize('approve-referee-team');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterLeague()
    {
        $this->resetPage();
    }

    public function updatedFilterSeason()
    {
        $this->resetPage();
    }

    /**
     * Получение матчей, требующих утверждения судейской бригады
     * Матчи, у которых есть заявки с operation_id == 2, judge_response == 1 и final_status == 0
     */
    public function getMatches()
    {
        $query = MatchEntity::with([
            'league',
            'season',
            'stadium.city',
            'city',
            'operation',
            'ownerClub',
            'guestClub',
            'match_judges' => function($q) {
                // Только те, кто согласился и ожидают утверждения директора
                $q->where('judge_response', 1)
                  ->where('final_status', 0)
                  ->where('operation_id', 2);
            },
            'match_judges.user',
            'match_judges.judge_type',
        ])
        ->whereHas('match_judges', function($q) {
            // Проверяем, что есть хотя бы одна заявка с operation_id == 2
            $q->where('judge_response', 1)
              ->where('final_status', 0)
              ->where('operation_id', 2);
        });

        // Поиск
        if ($this->search) {
            $query->where(function($q) {
                $q->whereHas('league', function($leagueQuery) {
                    $leagueQuery->where('title_ru', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('stadium', function($stadiumQuery) {
                    $stadiumQuery->where('title_ru', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('club', function($clubQuery) {
                    $clubQuery->where('title_ru', 'like', '%' . $this->search . '%')
                             ->orWhere('short_name_ru', 'like', '%' . $this->search . '%');
                });
            });
        }

        // Фильтр по лиге
        if ($this->filterLeague) {
            $query->where('league_id', $this->filterLeague);
        }

        // Фильтр по сезону
        if ($this->filterSeason) {
            $query->where('season_id', $this->filterSeason);
        }

        return $query->orderBy('start_at', 'asc')->paginate(12);
    }

    /**
     * Получение списка лиг для фильтра
     */
    public function getLeagues()
    {
        return \App\Models\League::where('is_active', true)
            ->orderBy('title_ru')
            ->get();
    }

    /**
     * Получение списка сезонов для фильтра
     */
    public function getSeasons()
    {
        return \App\Models\Season::where('is_active', true)
            ->orderBy('title_ru', 'desc')
            ->get();
    }

    /**
     * Переход к деталям матча
     */
    public function viewMatch($matchId)
    {
        return redirect()->route('referee-team-approval-detail', ['id' => $matchId]);
    }

    public function render()
    {
        return view('livewire.referee-team-approval-cards', [
            'matches' => $this->getMatches(),
            'leagues' => $this->getLeagues(),
            'seasons' => $this->getSeasons(),
        ])->layout(get_user_layout());
    }
}
