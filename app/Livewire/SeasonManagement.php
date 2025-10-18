<?php

namespace App\Livewire;

use App\Models\Season;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

#[Title('Управление сезонами')]
class SeasonManagement extends Component
{
    use WithPagination;

    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingSeasonId = null;
    public $selectedSeason = null;

    // Поиск и фильтрация
    public $search = '';
    public $filterStatus = '';

    protected $paginationTheme = 'tailwind';

    #[Validate('required|string|max:255')]
    public $titleRu = '';

    #[Validate('required|string|max:255')]
    public $titleKk = '';

    #[Validate('required|string|max:255')]
    public $titleEn = '';

    #[Validate('required|string|max:50|unique:seasons,value')]
    public $value = '';

    #[Validate('required|date')]
    public $startAt = '';

    #[Validate('required|date|after_or_equal:startAt')]
    public $endAt = '';

    public $isActive = true;

    #[Locked]
    public $canCreate = false;

    #[Locked]
    public $canEdit = false;

    #[Locked]
    public $canDelete = false;

    public function mount()
    {
        $this->authorize('manage-seasons');

        $user = auth()->user();
        $this->canCreate = $user->can('create-seasons');
        $this->canEdit = $user->can('manage-seasons');
        $this->canDelete = $user->can('delete-seasons');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function getSeasons()
    {
        $query = Season::query();

        // Поиск
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('title_en', 'like', '%' . $this->search . '%')
                  ->orWhere('value', 'like', '%' . $this->search . '%');
            });
        }

        // Фильтр по статусу
        if ($this->filterStatus !== '' && $this->filterStatus !== null) {
            $query->where('is_active', $this->filterStatus === '1');
        }

        return $query->orderBy('start_at', 'desc')->paginate(10);
    }

    public function createSeason()
    {
        $this->authorize('create-seasons');

        $this->validate();

        Season::create([
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
            'value' => $this->value,
            'start_at' => $this->startAt,
            'end_at' => $this->endAt,
            'is_active' => (bool) $this->isActive,
        ]);

        $this->reset(['titleRu', 'titleKk', 'titleEn', 'value', 'startAt', 'endAt', 'isActive', 'showCreateModal']);

        session()->flash('message', 'Сезон успешно создан');

        // Перерисовываем компонент
        $this->render();
    }

    public function editSeason($seasonId)
    {
        $season = Season::findOrFail($seasonId);
        $this->authorize('manage-seasons');

        $this->editingSeasonId = $season->id;
        $this->titleRu = $season->title_ru;
        $this->titleKk = $season->title_kk;
        $this->titleEn = $season->title_en;
        $this->value = $season->value;
        $this->startAt = $season->start_at->format('Y-m-d');
        $this->endAt = $season->end_at->format('Y-m-d');
        $this->isActive = $season->is_active;

        $this->showEditModal = true;
    }

    public function updateSeason()
    {
        $this->authorize('manage-seasons');

        $season = Season::findOrFail($this->editingSeasonId);

        $this->validate([
            'titleRu' => 'required|string|max:255',
            'titleKk' => 'required|string|max:255',
            'titleEn' => 'required|string|max:255',
            'value' => 'required|string|max:50|unique:seasons,value,' . $this->editingSeasonId,
            'startAt' => 'required|date',
            'endAt' => 'required|date|after_or_equal:startAt',
        ]);

        $season->update([
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
            'value' => $this->value,
            'start_at' => $this->startAt,
            'end_at' => $this->endAt,
            'is_active' => (bool) $this->isActive,
        ]);

        $this->reset(['titleRu', 'titleKk', 'titleEn', 'value', 'startAt', 'endAt', 'isActive', 'showEditModal', 'editingSeasonId']);

        session()->flash('message', 'Сезон успешно обновлен');

        // Перерисовываем компонент
        $this->render();
    }

    public function deleteSeason($seasonId)
    {
        $this->authorize('delete-seasons');

        $season = Season::findOrFail($seasonId);

        // Проверяем, есть ли связанные матчи
        if ($season->matches()->count() > 0) {
            session()->flash('error', 'Нельзя удалить сезон, так как с ним связаны матчи');
            return;
        }

        $season->delete();

        session()->flash('message', 'Сезон успешно удален');
    }

    public function toggleSeasonStatus($seasonId)
    {
        $this->authorize('manage-seasons');

        $season = Season::findOrFail($seasonId);
        $season->is_active = !$season->is_active;
        $season->save();

        session()->flash('message', 'Статус сезона изменен');
    }

    public function render()
    {
        return view('livewire.season-management', [
            'seasons' => $this->getSeasons(),
        ])->layout(get_user_layout());
    }
}