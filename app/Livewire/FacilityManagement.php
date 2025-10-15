<?php

namespace App\Livewire;

use App\Models\Facility;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

#[Title('Управление удобствами')]
#[Layout('layouts.admin')]
class FacilityManagement extends Component
{
    use WithPagination;

    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingFacilityId = null;

    // Поиск
    public $search = '';

    protected $paginationTheme = 'tailwind';

    // Для создания нового удобства
    #[Validate('required|string|max:255')]
    public $titleRu = '';

    #[Validate('nullable|string|max:255')]
    public $titleKk = '';

    #[Validate('nullable|string|max:255')]
    public $titleEn = '';

    // Для редактирования
    #[Validate('required|string|max:255')]
    public $editTitleRu = '';

    #[Validate('nullable|string|max:255')]
    public $editTitleKk = '';

    #[Validate('nullable|string|max:255')]
    public $editTitleEn = '';

    #[Locked]
    public $canCreate = false;

    #[Locked]
    public $canEdit = false;

    #[Locked]
    public $canDelete = false;

    public function mount()
    {
        $this->authorize('manage-facilities');

        $user = auth()->user();
        $this->canCreate = $user->can('create-facilities');
        $this->canEdit = $user->can('manage-facilities');
        $this->canDelete = $user->can('delete-facilities');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function getFacilities()
    {
        $query = Facility::query();

        // Поиск по названию
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('title_en', 'like', '%' . $this->search . '%');
            });
        }

        return $query->orderBy('title_ru', 'asc')->paginate(10);
    }

    public function createFacility()
    {
        $this->authorize('create-facilities');

        $this->validate([
            'titleRu' => 'required|string|max:255',
            'titleKk' => 'nullable|string|max:255',
            'titleEn' => 'nullable|string|max:255',
        ]);

        Facility::create([
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
        ]);

        $this->reset(['titleRu', 'titleKk', 'titleEn', 'showCreateModal']);

        session()->flash('message', 'Удобство успешно создано');

        // Перерисовываем компонент
        $this->render();
    }

    public function editFacility($facilityId)
    {
        $facility = Facility::findOrFail($facilityId);
        $this->authorize('manage-facilities');

        $this->editingFacilityId = $facility->id;
        $this->editTitleRu = $facility->title_ru;
        $this->editTitleKk = $facility->title_kk;
        $this->editTitleEn = $facility->title_en;

        $this->showEditModal = true;
    }

    public function updateFacility()
    {
        $this->authorize('manage-facilities');

        $facility = Facility::findOrFail($this->editingFacilityId);

        $this->validate([
            'editTitleRu' => 'required|string|max:255',
            'editTitleKk' => 'nullable|string|max:255',
            'editTitleEn' => 'nullable|string|max:255',
        ]);

        $facility->update([
            'title_ru' => $this->editTitleRu,
            'title_kk' => $this->editTitleKk,
            'title_en' => $this->editTitleEn,
        ]);

        $this->reset(['editTitleRu', 'editTitleKk', 'editTitleEn', 'showEditModal', 'editingFacilityId']);

        session()->flash('message', 'Удобство успешно обновлено');

        // Перерисовываем компонент
        $this->render();
    }

    public function deleteFacility($facilityId)
    {
        $this->authorize('delete-facilities');

        $facility = Facility::findOrFail($facilityId);

        $facility->delete();

        session()->flash('message', 'Удобство успешно удалено');
    }

    public function render()
    {
        return view('livewire.facility-management', [
            'facilities' => $this->getFacilities(),
        ]);
    }
}