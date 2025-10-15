<?php

namespace App\Livewire;

use App\Models\ClubStadium;
use App\Models\Club;
use App\Models\Stadium;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Управление связями клубов и стадионов')]
#[Layout('layouts.admin')]
class ClubStadiumManagement extends Component
{
    use WithPagination;

    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingClubStadiumId = null;

    // Поиск и фильтрация
    public $search = '';
    public $filterClub = '';
    public $filterStadium = '';
    public $filterCity = '';

    protected $paginationTheme = 'tailwind';

    public $clubs = [];
    public $stadiums = [];

    // Для создания новой связи
    public $clubId = '';

    public $stadiumId = '';

    // Для редактирования
    public $editClubId = '';
    public $editStadiumId = '';

    protected function rules()
    {
        return [
            'clubId' => 'required|exists:clubs,id',
            'stadiumId' => 'required|exists:stadiums,id',
            'editClubId' => 'required|exists:clubs,id',
            'editStadiumId' => 'required|exists:stadiums,id',
        ];
    }

    #[Locked]
    public $canCreate = false;

    #[Locked]
    public $canEdit = false;

    #[Locked]
    public $canDelete = false;

    public function mount()
    {
        $this->authorize('manage-club-stadiums');

        $user = auth()->user();
        $this->canCreate = $user->can('create-club-stadiums');
        $this->canEdit = $user->can('manage-club-stadiums');
        $this->canDelete = $user->can('delete-club-stadiums');

        $this->loadClubs();
        $this->loadStadiums();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterClub()
    {
        $this->resetPage();
    }

    public function updatedFilterStadium()
    {
        $this->resetPage();
    }

    public function updatedFilterCity()
    {
        $this->resetPage();
    }

    public function getClubStadiums()
    {
        $query = ClubStadium::with(['club', 'club.city', 'stadium']);

        // Поиск по названию клуба или стадиона
        if ($this->search) {
            $query->where(function($q) {
                $q->whereHas('club', function($subQuery) {
                    $subQuery->where('short_name_ru', 'like', '%' . $this->search . '%')
                          ->orWhere('short_name_kk', 'like', '%' . $this->search . '%')
                          ->orWhere('short_name_en', 'like', '%' . $this->search . '%')
                          ->orWhere('full_name_ru', 'like', '%' . $this->search . '%')
                          ->orWhere('full_name_kk', 'like', '%' . $this->search . '%')
                          ->orWhere('full_name_en', 'like', '%' . $this->search . '%');
                })->orWhereHas('stadium', function($subQuery) {
                    $subQuery->where('title_ru', 'like', '%' . $this->search . '%')
                          ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                          ->orWhere('title_en', 'like', '%' . $this->search . '%')
                          ->orWhere('address_ru', 'like', '%' . $this->search . '%')
                          ->orWhere('address_kk', 'like', '%' . $this->search . '%')
                          ->orWhere('address_en', 'like', '%' . $this->search . '%');
                });
            });
        }

        // Фильтр по клубу
        if (!empty($this->filterClub)) {
            $query->where('club_id', $this->filterClub);
        }

        // Фильтр по стадиону
        if (!empty($this->filterStadium)) {
            $query->where('stadium_id', $this->filterStadium);
        }

        // Фильтр по городу (через клуб)
        if (!empty($this->filterCity)) {
            $query->whereHas('club', function($subQuery) {
                $subQuery->where('city_id', $this->filterCity);
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function loadClubs()
    {
        $this->clubs = Club::where('is_active', true)
            ->with('city')
            ->orderBy('short_name_ru')
            ->get();
    }

    public function loadStadiums()
    {
        $this->stadiums = Stadium::where('is_active', true)
            ->orderBy('title_ru')
            ->get();
    }

    public function createClubStadium()
    {
        $this->authorize('create-club-stadiums');

        $this->validate([
            'clubId' => 'required|exists:clubs,id',
            'stadiumId' => 'required|exists:stadiums,id',
        ]);

        // Проверяем, что такая связь еще не существует
        $exists = ClubStadium::where('club_id', $this->clubId)
            ->where('stadium_id', $this->stadiumId)
            ->exists();

        if ($exists) {
            session()->flash('error', 'Такая связь уже существует');
            return;
        }

        ClubStadium::create([
            'club_id' => $this->clubId,
            'stadium_id' => $this->stadiumId,
        ]);

        $this->reset(['clubId', 'stadiumId', 'showCreateModal']);

        session()->flash('message', 'Связь успешно создана');
    }

    public function editClubStadium($clubStadiumId)
    {
        $clubStadium = ClubStadium::findOrFail($clubStadiumId);
        $this->authorize('manage-club-stadiums');

        $this->editingClubStadiumId = $clubStadium->id;
        $this->editClubId = $clubStadium->club_id;
        $this->editStadiumId = $clubStadium->stadium_id;

        $this->showEditModal = true;
    }

    public function updateClubStadium()
    {
        $this->authorize('manage-club-stadiums');

        $clubStadium = ClubStadium::findOrFail($this->editingClubStadiumId);

        $this->validate([
            'editClubId' => 'required|exists:clubs,id',
            'editStadiumId' => 'required|exists:stadiums,id',
        ]);

        // Проверяем, что такая связь еще не существует (исключая текущую)
        $exists = ClubStadium::where('club_id', $this->editClubId)
            ->where('stadium_id', $this->editStadiumId)
            ->where('id', '!=', $this->editingClubStadiumId)
            ->exists();

        if ($exists) {
            session()->flash('error', 'Такая связь уже существует');
            return;
        }

        $clubStadium->update([
            'club_id' => $this->editClubId,
            'stadium_id' => $this->editStadiumId,
        ]);

        $this->reset(['editClubId', 'editStadiumId', 'showEditModal', 'editingClubStadiumId']);

        session()->flash('message', 'Связь успешно обновлена');
    }

    public function deleteClubStadium($clubStadiumId)
    {
        $this->authorize('delete-club-stadiums');

        $clubStadium = ClubStadium::findOrFail($clubStadiumId);

        // Проверяем, есть ли связанные матчи
        if ($clubStadium->club->matches()->count() > 0) {
            session()->flash('error', 'Нельзя удалить связь, так как у клуба есть запланированные матчи');
            return;
        }

        if ($clubStadium->stadium->matches()->count() > 0) {
            session()->flash('error', 'Нельзя удалить связь, так как у стадиона есть запланированные матчи');
            return;
        }

        $clubStadium->delete();

        session()->flash('message', 'Связь успешно удалена');
    }

    public function render()
    {
        return view('livewire.club-stadium-management', [
            'clubStadiums' => $this->getClubStadiums(),
        ]);
    }
}