<?php

namespace App\Livewire;

use App\Models\MatchEntity;
use App\Models\League;
use App\Models\Season;
use App\Models\Club;
use App\Models\Operation;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;

#[Title('Назначение судей на матч')]
class MatchAssignmentCards extends Component
{
    use WithPagination;

    // Фильтры
    public $search = '';
    public $leagueFilter = '';
    public $seasonFilter = '';
    public $operationFilter = '';

    // Доступные данные для фильтров
    public $leagues = [];
    public $seasons = [];
    public $operations = [];

    public function mount()
    {
        // Проверка прав доступа
        $this->authorize('assign-referees');

        // Загрузка данных для фильтров
        $this->leagues = League::orderBy('title_ru')->get();
        $this->seasons = Season::orderBy('start_at', 'desc')->get();

        // Только операции, связанные с назначением судей
        $this->operations = Operation::whereIn('value', [
            'match_created_waiting_referees',
            'referee_reassignment',
            'referee_team_approval'
        ])->get();
    }

    /**
     * Сброс пагинации при изменении фильтров
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingLeagueFilter()
    {
        $this->resetPage();
    }

    public function updatingSeasonFilter()
    {
        $this->resetPage();
    }

    public function updatingOperationFilter()
    {
        $this->resetPage();
    }

    /**
     * Сброс всех фильтров
     */
    public function resetFilters()
    {
        $this->reset(['search', 'leagueFilter', 'seasonFilter', 'operationFilter']);
        $this->resetPage();
    }

    /**
     * Переход на страницу детального назначения судей
     */
    public function viewMatchDetail($matchId)
    {
        return redirect()->route('match-assignment-detail', ['id' => $matchId]);
    }

    public function render()
    {
        // Получение матчей с фильтрацией
        // Только матчи в статусах: match_created_waiting_referees, referee_reassignment, referee_team_approval
        $matches = MatchEntity::query()
            ->with([
                'league',
                'season',
                'operation',
                'stadium.city',
                'city',
                'judge_requirements.judge_type',
                'match_judges' => function($query) {
                    // Загружаем судей для подсчета статусов
                    $query->with('user', 'judge_type');
                }
            ])
            ->whereHas('operation', function($query) {
                $query->whereIn('value', [
                    'match_created_waiting_referees',
                    'referee_reassignment',
                    'referee_team_approval'
                ]);
            })
            // Фильтр по поиску (поиск по командам)
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->whereHas('club', function($clubQuery) {
                        $clubQuery->where('title_ru', 'like', '%' . $this->search . '%')
                                  ->orWhere('short_name_ru', 'like', '%' . $this->search . '%');
                    })
                    ->orWhere('owner_club_id', 'like', '%' . $this->search . '%')
                    ->orWhere('guest_club_id', 'like', '%' . $this->search . '%');
                });
            })
            // Фильтр по лиге
            ->when($this->leagueFilter, function($query) {
                $query->where('league_id', $this->leagueFilter);
            })
            // Фильтр по сезону
            ->when($this->seasonFilter, function($query) {
                $query->where('season_id', $this->seasonFilter);
            })
            // Фильтр по операции
            ->when($this->operationFilter, function($query) {
                $query->where('operation_id', $this->operationFilter);
            })
            ->orderBy('start_at', 'desc')
            ->paginate(12);

        // Для каждого матча вычисляем статистику назначений
        $matches->each(function($match) {
            // Подсчет количества судей в различных статусах
            $match->approved_count = $match->match_judges->where('judge_response', 1)->where('final_status', 1)->count();
            $match->waiting_response_count = $match->match_judges->where('judge_response', 0)->count();
            $match->waiting_director_count = $match->match_judges->where('judge_response', 1)->where('final_status', 0)->count();
            $match->rejected_count = $match->match_judges->where(function($j) {
                return $j->judge_response == -1 || $j->final_status == -1;
            })->count();

            // Общее количество требуемых судей
            $match->required_judges_count = $match->judge_requirements->where('is_required', true)->sum('qty');

            // Прогресс назначения (процент утвержденных от требуемых)
            $match->assignment_progress = $match->required_judges_count > 0
                ? round(($match->approved_count / $match->required_judges_count) * 100)
                : 0;
        });

        return view('livewire.match-assignment-cards', [
            'matches' => $matches
        ])->layout(get_user_layout());
    }
}
