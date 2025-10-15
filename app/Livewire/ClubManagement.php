<?php

namespace App\Livewire;

use App\Models\Club;
use App\Models\City;
use App\Models\ClubType;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;

#[Title('Управление клубами')]
#[Layout('layouts.admin')]
class ClubManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingClubId = null;
    public $selectedClub = null;

    // Поиск и фильтрация
    public $search = '';
    public $filterCity = '';
    public $filterType = '';
    public $filterStatus = '';
    public $filterParent = '';

    protected $paginationTheme = 'tailwind';

    public $cities = [];
    public $clubTypes = [];
    public $parentClubs = [];

    #[Validate('nullable|image|max:5120')] // max 5MB
    public $image = null;

    #[Validate('nullable|image|max:5120')] // max 5MB
    public $editImage = null;

    public $selectedMedia = [];

    #[Validate('nullable|integer|exists:clubs,id')]
    public $parentId = '';

    #[Validate('required|integer|exists:cities,id')]
    public $cityId = '';

    #[Validate('required|integer|exists:club_types,id')]
    public $typeId = '';

    #[Validate('required|string|max:100')]
    public $shortNameRu = '';

    #[Validate('required|string|max:100')]
    public $shortNameKk = '';

    #[Validate('required|string|max:100')]
    public $shortNameEn = '';

    #[Validate('required|string|max:255')]
    public $fullNameRu = '';

    #[Validate('required|string|max:255')]
    public $fullNameKk = '';

    #[Validate('required|string|max:255')]
    public $fullNameEn = '';

    #[Validate('nullable|string')]
    public $descriptionRu = '';

    #[Validate('nullable|string')]
    public $descriptionKk = '';

    #[Validate('nullable|string')]
    public $descriptionEn = '';

    #[Validate('nullable|string|max:12')]
    public $bin = '';

    #[Validate('nullable|date')]
    public $foundationDate = '';

    #[Validate('nullable|string|max:500')]
    public $addressRu = '';

    #[Validate('nullable|string|max:500')]
    public $addressKk = '';

    #[Validate('nullable|string|max:500')]
    public $addressEn = '';

    #[Validate('nullable|array')]
    public $phone = [];

    #[Validate('nullable|string|max:255')]
    public $website = '';

    public $isActive = true;

    #[Locked]
    public $canCreate = false;

    #[Locked]
    public $canEdit = false;

    #[Locked]
    public $canDelete = false;

    public function mount()
    {
        $this->authorize('manage-clubs');

        $user = auth()->user();
        $this->canCreate = $user->can('create-clubs');
        $this->canEdit = $user->can('manage-clubs');
        $this->canDelete = $user->can('delete-clubs');

        $this->loadCities();
        $this->loadClubTypes();
        $this->loadParentClubs();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterCity()
    {
        $this->resetPage();
    }

    public function updatedFilterType()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedFilterParent()
    {
        $this->resetPage();
    }

    public function getClubs()
    {
        $query = Club::with(['city', 'club_type', 'club']);

        // Поиск
        if ($this->search) {
            $query->where(function($q) {
                $q->where('short_name_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('short_name_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('short_name_en', 'like', '%' . $this->search . '%')
                  ->orWhere('full_name_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('full_name_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('full_name_en', 'like', '%' . $this->search . '%')
                  ->orWhere('bin', 'like', '%' . $this->search . '%');
            });
        }

        // Фильтр по городу
        if (!empty($this->filterCity)) {
            $query->where('city_id', $this->filterCity);
        }

        // Фильтр по типу клуба
        if (!empty($this->filterType)) {
            $query->where('type_id', $this->filterType);
        }

        // Фильтр по статусу
        if ($this->filterStatus !== '' && $this->filterStatus !== null) {
            $query->where('is_active', $this->filterStatus === '1');
        }

        // Фильтр по родительскому клубу
        if (!empty($this->filterParent)) {
            $query->where('parent_id', $this->filterParent === 'null' ? null : $this->filterParent);
        }

        return $query->orderBy('full_name_ru')->paginate(10);
    }

    public function loadCities()
    {
        $this->cities = City::where('is_active', true)->get();
    }

    public function loadClubTypes()
    {
        $this->clubTypes = ClubType::where('is_active', true)->get();
    }

    public function loadParentClubs()
    {
        $this->parentClubs = Club::where('is_active', true)->whereNull('parent_id')->get();
    }

    public function createClub()
    {
        $this->authorize('create-clubs');

        $this->validate([
            'parentId' => 'nullable|integer|exists:clubs,id',
            'cityId' => 'required|integer|exists:cities,id',
            'typeId' => 'required|integer|exists:club_types,id',
            'shortNameRu' => 'required|string|max:100',
            'shortNameKk' => 'required|string|max:100',
            'shortNameEn' => 'required|string|max:100',
            'fullNameRu' => 'required|string|max:255',
            'fullNameKk' => 'required|string|max:255',
            'fullNameEn' => 'required|string|max:255',
            'descriptionRu' => 'nullable|string',
            'descriptionKk' => 'nullable|string',
            'descriptionEn' => 'nullable|string',
            'bin' => 'nullable|string|max:12',
            'foundationDate' => 'nullable|date',
            'addressRu' => 'nullable|string|max:500',
            'addressKk' => 'nullable|string|max:500',
            'addressEn' => 'nullable|string|max:500',
            'phone' => 'nullable|array',
            'website' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:5120',
        ]);

        $club = Club::create([
            'parent_id' => $this->parentId ?: null,
            'city_id' => $this->cityId,
            'type_id' => $this->typeId,
            'short_name_ru' => $this->shortNameRu,
            'short_name_kk' => $this->shortNameKk,
            'short_name_en' => $this->shortNameEn,
            'full_name_ru' => $this->fullNameRu,
            'full_name_kk' => $this->fullNameKk,
            'full_name_en' => $this->fullNameEn,
            'description_ru' => $this->descriptionRu,
            'description_kk' => $this->descriptionKk,
            'description_en' => $this->descriptionEn,
            'bin' => $this->bin,
            'foundation_date' => $this->foundationDate ?: null,
            'address_ru' => $this->addressRu,
            'address_kk' => $this->addressKk,
            'address_en' => $this->addressEn,
            'phone' => $this->phone,
            'website' => $this->website,
            'is_active' => (bool) $this->isActive,
        ]);

        // Handle image upload
        if ($this->image) {
            $media = $club->addMedia($this->image->getRealPath())
                  ->usingName($this->image->getClientOriginalName())
                  ->usingFileName($this->image->getClientOriginalName())
                  ->toMediaCollection('image');

            // Update image_url field with relative path
            $club->update(['image_url' => $media->getUrl()]);
        }

        $this->reset(['selectedMedia', 'image', 'parentId', 'cityId', 'typeId', 'shortNameRu', 'shortNameKk', 'shortNameEn', 'fullNameRu', 'fullNameKk', 'fullNameEn', 'descriptionRu', 'descriptionKk', 'descriptionEn', 'bin', 'foundationDate', 'addressRu', 'addressKk', 'addressEn', 'phone', 'website', 'isActive', 'showCreateModal']);

        session()->flash('message', 'Клуб успешно создан');

        // Перерисовываем компонент
        $this->render();
    }

    public function editClub($clubId)
    {
        $club = Club::findOrFail($clubId);
        $this->authorize('manage-clubs');

        $this->editingClubId = $club->id;
        $this->selectedMedia = []; // For media selection
        $this->editImage = null; // For new image upload
        $this->parentId = $club->parent_id;
        $this->cityId = $club->city_id;
        $this->typeId = $club->type_id;
        $this->shortNameRu = $club->short_name_ru;
        $this->shortNameKk = $club->short_name_kk;
        $this->shortNameEn = $club->short_name_en;
        $this->fullNameRu = $club->full_name_ru;
        $this->fullNameKk = $club->full_name_kk;
        $this->fullNameEn = $club->full_name_en;
        $this->descriptionRu = $club->description_ru;
        $this->descriptionKk = $club->description_kk;
        $this->descriptionEn = $club->description_en;
        $this->bin = $club->bin;
        $this->foundationDate = $club->foundation_date ? $club->foundation_date->format('Y-m-d') : '';
        $this->addressRu = $club->address_ru;
        $this->addressKk = $club->address_kk;
        $this->addressEn = $club->address_en;
        $this->phone = $club->phone ?: [];
        $this->website = $club->website;
        $this->isActive = $club->is_active;

        $this->showEditModal = true;
    }

    public function updateClub()
    {
        $this->authorize('manage-clubs');

        $club = Club::findOrFail($this->editingClubId);

        $this->validate([
            'editImage' => 'nullable|image|max:5120',
            'parentId' => 'nullable|integer|exists:clubs,id',
            'cityId' => 'required|integer|exists:cities,id',
            'typeId' => 'required|integer|exists:club_types,id',
            'shortNameRu' => 'required|string|max:100',
            'shortNameKk' => 'required|string|max:100',
            'shortNameEn' => 'required|string|max:100',
            'fullNameRu' => 'required|string|max:255',
            'fullNameKk' => 'required|string|max:255',
            'fullNameEn' => 'required|string|max:255',
            'descriptionRu' => 'nullable|string',
            'descriptionKk' => 'nullable|string',
            'descriptionEn' => 'nullable|string',
            'bin' => 'nullable|string|max:12',
            'foundationDate' => 'nullable|date',
            'addressRu' => 'nullable|string|max:500',
            'addressKk' => 'nullable|string|max:500',
            'addressEn' => 'nullable|string|max:500',
            'phone' => 'nullable|array',
            'website' => 'nullable|string|max:255',
        ]);

        $club->update([
            'parent_id' => $this->parentId ?: null,
            'city_id' => $this->cityId,
            'type_id' => $this->typeId,
            'short_name_ru' => $this->shortNameRu,
            'short_name_kk' => $this->shortNameKk,
            'short_name_en' => $this->shortNameEn,
            'full_name_ru' => $this->fullNameRu,
            'full_name_kk' => $this->fullNameKk,
            'full_name_en' => $this->fullNameEn,
            'description_ru' => $this->descriptionRu,
            'description_kk' => $this->descriptionKk,
            'description_en' => $this->descriptionEn,
            'bin' => $this->bin,
            'foundation_date' => $this->foundationDate ?: null,
            'address_ru' => $this->addressRu,
            'address_kk' => $this->addressKk,
            'address_en' => $this->addressEn,
            'phone' => $this->phone,
            'website' => $this->website,
            'is_active' => (bool) $this->isActive,
        ]);

        // Handle image upload
        if ($this->editImage) {
            // Clear existing images and add new one
            $club->clearMediaCollection('image');
            $media = $club->addMedia($this->editImage->getRealPath())
                  ->usingName($this->editImage->getClientOriginalName())
                  ->usingFileName($this->editImage->getClientOriginalName())
                  ->toMediaCollection('image');

            // Update image_url field with relative path
            $club->update(['image_url' => $media->getUrl()]);
        }

        $this->reset(['selectedMedia', 'editImage', 'parentId', 'cityId', 'typeId', 'shortNameRu', 'shortNameKk', 'shortNameEn', 'fullNameRu', 'fullNameKk', 'fullNameEn', 'descriptionRu', 'descriptionKk', 'descriptionEn', 'bin', 'foundationDate', 'addressRu', 'addressKk', 'addressEn', 'phone', 'website', 'isActive', 'showEditModal', 'editingClubId']);

        session()->flash('message', 'Клуб успешно обновлен');

        // Перерисовываем компонент
        $this->render();
    }

    public function deleteClub($clubId)
    {
        $this->authorize('delete-clubs');

        $club = Club::findOrFail($clubId);

        // Проверяем, есть ли связанные дочерние клубы
        if ($club->clubs()->count() > 0) {
            session()->flash('error', 'Нельзя удалить клуб, так как с ним связаны дочерние клубы');
            return;
        }

        // Проверяем, есть ли связанные матчи
        if ($club->matches()->count() > 0) {
            session()->flash('error', 'Нельзя удалить клуб, так как с ним связаны матчи');
            return;
        }

        // Delete associated media
        $club->media()->delete();

        $club->delete();

        session()->flash('message', 'Клуб успешно удален');
    }

    public function toggleClubStatus($clubId)
    {
        $this->authorize('manage-clubs');

        $club = Club::findOrFail($clubId);
        $club->is_active = !$club->is_active;
        $club->save();

        session()->flash('message', 'Статус клуба изменен');
    }

    public function removeCurrentImage()
    {
        $this->authorize('manage-clubs');

        $club = Club::findOrFail($this->editingClubId);
        $club->clearMediaCollection('image');

        // Clear image_url field
        $club->update(['image_url' => null]);

        session()->flash('message', 'Изображение клуба удалено');
    }

    public function render()
    {
        return view('livewire.club-management', [
            'clubs' => $this->getClubs(),
        ]);
    }
}