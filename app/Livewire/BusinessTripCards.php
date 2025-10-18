<?php

namespace App\Livewire;

use App\Models\MatchEntity;
use App\Models\Operation;
use App\Models\League;
use App\Models\Season;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Командировки')]
class BusinessTripCards extends Component
{
    use WithPagination;

    // Фильтры
    public $search = '';
    public $operationFilter = '';
    public $leagueFilter = '';
    public $seasonFilter = '';

    // Данные для фильтров
    public $operations = [];
    public $leagues = [];
    public $seasons = [];

    public function mount()
    {
        // Проверка прав доступа
        $this->authorize('manage-logistics');

        // Загрузка операций связанных с командировками
        $this->operations = Operation::whereIn('value', [
            'select_responsible_logisticians',
            'select_transport_departure',
            'business_trip_plan_preparation',
            'referee_team_confirmation',
            'primary_business_trip_confirmation',
            'final_business_trip_confirmation',
            'business_trip_registration',
            'business_trip_plan_reprocessing'
        ])->get();

        // Загрузка лиг и сезонов
        $this->leagues = League::orderBy('title_ru')->get();
        $this->seasons = Season::orderBy('start_at', 'desc')->get();
    }

    /**
     * Сброс пагинации при изменении фильтров
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingOperationFilter()
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

    /**
     * Сброс всех фильтров
     */
    public function resetFilters()
    {
        $this->reset(['search', 'operationFilter', 'leagueFilter', 'seasonFilter']);
        $this->resetPage();
    }

    /**
     * Переход на страницу детального просмотра командировки
     */
    public function viewTripDetail($matchId)
    {
        return redirect()->route('business-trip-detail', ['id' => $matchId]);
    }

    public function render()
    {
        // Получение матчей с фильтрацией
        $matches = MatchEntity::query()
            ->with([
                'league',
                'season',
                'operation',
                'stadium.city',
                'city',
                'ownerClub',
                'guestClub',
                'trips' => function($query) {
                    $query->with('user');
                }
            ])
            ->whereHas('operation', function($query) {
                $query->whereIn('value', [
                    'select_responsible_logisticians',
                    'select_transport_departure',
                    'business_trip_plan_preparation',
                    'referee_team_confirmation',
                    'primary_business_trip_confirmation',
                    'final_business_trip_confirmation',
                    'business_trip_registration',
                    'business_trip_plan_reprocessing'
                ]);
            })
            // Фильтр по поиску
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->whereHas('ownerClub', function($clubQuery) {
                        $clubQuery->where('title_ru', 'like', '%' . $this->search . '%')
                                  ->orWhere('short_name_ru', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('guestClub', function($clubQuery) {
                        $clubQuery->where('title_ru', 'like', '%' . $this->search . '%')
                                  ->orWhere('short_name_ru', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('stadium', function($stadiumQuery) {
                        $stadiumQuery->where('title_ru', 'like', '%' . $this->search . '%');
                    });
                });
            })
            // Фильтр по операции
            ->when($this->operationFilter, function($query) {
                $query->where('current_operation_id', $this->operationFilter);
            })
            // Фильтр по лиге
            ->when($this->leagueFilter, function($query) {
                $query->where('league_id', $this->leagueFilter);
            })
            // Фильтр по сезону
            ->when($this->seasonFilter, function($query) {
                $query->where('season_id', $this->seasonFilter);
            })
            ->orderBy('start_at', 'desc')
            ->paginate(12);

        // Для каждого матча вычисляем статистику
        $matches->each(function($match) {
            // Количество командировок
            $match->trips_count = $match->trips->count();

            // Количество подтвержденных командировок
            $match->confirmed_trips_count = $match->trips->filter(function($trip) {
                return $trip->is_confirmed == true;
            })->count();
        });

        return view('livewire.logistician.business-trip-cards', [
            'matches' => $matches
        ])->layout(get_user_layout());
    }
}
