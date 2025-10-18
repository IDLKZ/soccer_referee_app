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

    // Tabs
    public $activeTab = 'active'; // 'active' or 'all'

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
     * Переключение табов
     */
    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
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
        // Определяем активные операции командировок
        $activeOperations = [
            'select_responsible_logisticians',
            'select_transport_departure',
            'business_trip_plan_preparation',
            'referee_team_confirmation',
            'primary_business_trip_confirmation',
            'final_business_trip_confirmation',
            'business_trip_registration',
            'business_trip_plan_reprocessing'
        ];

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
            ->withCount('trips')
            // Фильтр по табам
            ->when($this->activeTab === 'active', function($query) use ($activeOperations) {
                // Только активные командировки
                $query->whereHas('operation', function($q) use ($activeOperations) {
                    $q->whereIn('value', $activeOperations);
                });
            })
            ->when($this->activeTab === 'all', function($query) {
                // Все матчи, у которых есть хотя бы одна командировка
                $query->whereHas('trips');
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

        // Считаем общее количество для табов
        $activeCount = MatchEntity::whereHas('operation', function($q) use ($activeOperations) {
            $q->whereIn('value', $activeOperations);
        })->count();

        $allCount = MatchEntity::whereHas('trips')->count();

        return view('livewire.logistician.business-trip-cards', [
            'matches' => $matches,
            'activeCount' => $activeCount,
            'allCount' => $allCount
        ])->layout(get_user_layout());
    }
}
