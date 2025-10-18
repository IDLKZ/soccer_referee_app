<?php

namespace App\Livewire;

use App\Models\Stadium;
use App\Models\City;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

#[Title('Управление стадионами')]
class StadiumManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingStadiumId = null;
    public $selectedStadium = null;

    // Поиск и фильтрация
    public $search = '';
    public $filterStatus = '';
    public $filterCity = '';
    public $filterCapacity = '';

    protected $paginationTheme = 'tailwind';

    public $cities = [];

    public $selectedMedia = [];

    #[Validate('required|exists:cities,id')]
    public $cityId = '';

    #[Validate('nullable|image|max:5120')] // max 5MB
    public $image = null;

    #[Validate('nullable|image|max:5120')] // max 5MB
    public $editImage = null;

    #[Validate('required|string|max:255')]
    public $titleRu = '';

    #[Validate('required|string|max:255')]
    public $titleKk = '';

    #[Validate('required|string|max:255')]
    public $titleEn = '';

    #[Validate('nullable|string')]
    public $descriptionRu = '';

    #[Validate('nullable|string')]
    public $descriptionKk = '';

    #[Validate('nullable|string')]
    public $descriptionEn = '';

    #[Validate('nullable|string')]
    public $addressRu = '';

    #[Validate('nullable|string')]
    public $addressKk = '';

    #[Validate('nullable|string')]
    public $addressEn = '';

    #[Validate('nullable|string|max:20')]
    public $lat = '';

    #[Validate('nullable|string|max:20')]
    public $lon = '';

    #[Validate('nullable|date')]
    public $builtDate = '';

    #[Validate('nullable|string|max:50')]
    public $phone = '';

    #[Validate('nullable|url|max:255')]
    public $website = '';

    public $capacity = 1;

    #[Validate('required|integer|min:1')]
    public $editCapacity = 1;

    public $isActive = true;

    #[Locked]
    public $canCreate = false;

    #[Locked]
    public $canEdit = false;

    #[Locked]
    public $canDelete = false;

    public function mount()
    {
        $this->authorize('manage-stadiums');

        $user = auth()->user();
        $this->canCreate = $user->can('create-stadiums');
        $this->canEdit = $user->can('manage-stadiums');
        $this->canDelete = $user->can('delete-stadiums');

        $this->loadCities();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedFilterCity()
    {
        $this->resetPage();
    }

    public function updatedFilterCapacity()
    {
        $this->resetPage();
    }

    public function getStadiums()
    {
        $query = Stadium::with('city')->withCount('matches');

        // Поиск
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('title_en', 'like', '%' . $this->search . '%')
                  ->orWhere('address_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('address_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('address_en', 'like', '%' . $this->search . '%')
                  ->orWhere('description_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('description_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('description_en', 'like', '%' . $this->search . '%');
            });
        }

        // Фильтр по городу
        if (!empty($this->filterCity)) {
            $query->where('city_id', $this->filterCity);
        }

        // Фильтр по вместимости
        if (!empty($this->filterCapacity)) {
            if ($this->filterCapacity === 'small') {
                $query->where('capacity', '<', 5000);
            } elseif ($this->filterCapacity === 'medium') {
                $query->whereBetween('capacity', [5000, 20000]);
            } elseif ($this->filterCapacity === 'large') {
                $query->where('capacity', '>', 20000);
            }
        }

        // Фильтр по статусу
        if ($this->filterStatus !== '' && $this->filterStatus !== null) {
            $query->where('is_active', $this->filterStatus === '1');
        }

        return $query->orderBy('title_ru')->paginate(10);
    }

    public function loadCities()
    {
        $this->cities = City::where('is_active', true)->get();
    }

    public function createStadium()
    {
        $this->authorize('create-stadiums');

        $this->validate([
            'cityId' => 'required|exists:cities,id',
            'titleRu' => 'required|string|max:255',
            'titleKk' => 'required|string|max:255',
            'titleEn' => 'required|string|max:255',
            'descriptionRu' => 'nullable|string',
            'descriptionKk' => 'nullable|string',
            'descriptionEn' => 'nullable|string',
            'addressRu' => 'nullable|string',
            'addressKk' => 'nullable|string',
            'addressEn' => 'nullable|string',
            'lat' => 'nullable|string|max:20',
            'lon' => 'nullable|string|max:20',
            'builtDate' => 'nullable|date',
            'phone' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'capacity' => 'required|integer|min:1',
            'image' => 'nullable|image|max:5120',
        ]);

        $stadium = Stadium::create([
            'city_id' => $this->cityId,
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
            'description_ru' => $this->descriptionRu,
            'description_kk' => $this->descriptionKk,
            'description_en' => $this->descriptionEn,
            'address_ru' => $this->addressRu,
            'address_kk' => $this->addressKk,
            'address_en' => $this->addressEn,
            'lat' => $this->lat ?: null,
            'lon' => $this->lon ?: null,
            'built_date' => $this->builtDate ?: null,
            'phone' => $this->phone,
            'website' => $this->website,
            'capacity' => $this->capacity,
            'is_active' => (bool) $this->isActive,
        ]);

        // Handle image upload
        if ($this->image) {
            $media = $stadium->addMedia($this->image->getRealPath())
                  ->usingName($this->image->getClientOriginalName())
                  ->usingFileName($this->image->getClientOriginalName())
                  ->toMediaCollection('image');

            // Update image_url field with relative path
            $stadium->update(['image_url' => $media->getUrl()]);
        }

        // Add selected media if any
        if (!empty($this->selectedMedia)) {
            foreach ($this->selectedMedia as $mediaId) {
                $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($mediaId);
                if ($media) {
                    $stadium->addMediaFromMedia($media)
                          ->toMediaCollection('image');
                }
            }
        }

        $this->reset(['selectedMedia', 'cityId', 'titleRu', 'titleKk', 'titleEn', 'descriptionRu', 'descriptionKk', 'descriptionEn', 'addressRu', 'addressKk', 'addressEn', 'lat', 'lon', 'builtDate', 'phone', 'website', 'capacity', 'isActive', 'showCreateModal', 'image']);

        session()->flash('message', 'Стадион успешно создан');

        // Перерисовываем компонент
        $this->render();
    }

    public function editStadium($stadiumId)
    {
        $stadium = Stadium::findOrFail($stadiumId);
        $this->authorize('manage-stadiums');

        $this->editingStadiumId = $stadium->id;
        $this->selectedMedia = []; // For media selection
        $this->cityId = $stadium->city_id;
        $this->titleRu = $stadium->title_ru;
        $this->titleKk = $stadium->title_kk;
        $this->titleEn = $stadium->title_en;
        $this->descriptionRu = $stadium->description_ru;
        $this->descriptionKk = $stadium->description_kk;
        $this->descriptionEn = $stadium->description_en;
        $this->addressRu = $stadium->address_ru;
        $this->addressKk = $stadium->address_kk;
        $this->addressEn = $stadium->address_en;
        $this->lat = $stadium->lat;
        $this->lon = $stadium->lon;
        $this->builtDate = $stadium->built_date ? $stadium->built_date->format('Y-m-d') : '';
        $this->phone = $stadium->phone;
        $this->website = $stadium->website;
        $this->editCapacity = $stadium->capacity;
        $this->isActive = $stadium->is_active;

        $this->showEditModal = true;
    }

    public function updateStadium()
    {
        $this->authorize('manage-stadiums');

        $stadium = Stadium::findOrFail($this->editingStadiumId);

        $this->validate([
            'cityId' => 'required|exists:cities,id',
            'titleRu' => 'required|string|max:255',
            'titleKk' => 'required|string|max:255',
            'titleEn' => 'required|string|max:255',
            'descriptionRu' => 'nullable|string',
            'descriptionKk' => 'nullable|string',
            'descriptionEn' => 'nullable|string',
            'addressRu' => 'nullable|string',
            'addressKk' => 'nullable|string',
            'addressEn' => 'nullable|string',
            'lat' => 'nullable|string|max:20',
            'lon' => 'nullable|string|max:20',
            'builtDate' => 'nullable|date',
            'phone' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'editCapacity' => 'required|integer|min:1',
            'editImage' => 'nullable|image|max:5120',
        ]);

        \Log::info('Before stadium update', [
            'stadium_id' => $stadium->id,
            'editCapacity' => $this->editCapacity,
            'editCapacity_type' => gettype($this->editCapacity),
            'capacity' => $this->capacity,
            'capacity_type' => gettype($this->capacity),
            'current_db_capacity' => $stadium->capacity
        ]);

        $stadium->update([
            'city_id' => $this->cityId,
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
            'description_ru' => $this->descriptionRu,
            'description_kk' => $this->descriptionKk,
            'description_en' => $this->descriptionEn,
            'address_ru' => $this->addressRu,
            'address_kk' => $this->addressKk,
            'address_en' => $this->addressEn,
            'lat' => $this->lat ?: null,
            'lon' => $this->lon ?: null,
            'built_date' => $this->builtDate ?: null,
            'phone' => $this->phone,
            'website' => $this->website,
            'capacity' => (int) $this->editCapacity,
            'is_active' => (bool) $this->isActive,
        ]);

        \Log::info('After stadium update', [
            'stadium_id' => $stadium->id,
            'new_db_capacity' => $stadium->fresh()->capacity
        ]);

        // Handle image upload
        if ($this->editImage) {
            // Clear existing images and add new one
            $stadium->clearMediaCollection('image');
            $media = $stadium->addMedia($this->editImage->getRealPath())
                  ->usingName($this->editImage->getClientOriginalName())
                  ->usingFileName($this->editImage->getClientOriginalName())
                  ->toMediaCollection('image');

            // Update image_url field with relative path
            $stadium->update(['image_url' => $media->getUrl()]);
        }

        // Update image if new media is selected
        if (!empty($this->selectedMedia)) {
            $stadium->clearMediaCollection('image');
            foreach ($this->selectedMedia as $mediaId) {
                $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($mediaId);
                if ($media) {
                    $stadium->addMediaFromMedia($media)
                          ->toMediaCollection('image');
                }
            }
        }

        $this->reset(['selectedMedia', 'cityId', 'titleRu', 'titleKk', 'titleEn', 'descriptionRu', 'descriptionKk', 'descriptionEn', 'addressRu', 'addressKk', 'addressEn', 'lat', 'lon', 'builtDate', 'phone', 'website', 'capacity', 'editCapacity', 'isActive', 'showEditModal', 'editingStadiumId', 'editImage']);

        session()->flash('message', 'Стадион успешно обновлен');

        // Перерисовываем компонент
        $this->render();
    }

    public function deleteStadium($stadiumId)
    {
        $this->authorize('delete-stadiums');

        $stadium = Stadium::findOrFail($stadiumId);

        // Проверяем, есть ли связанные матчи
        if ($stadium->matches()->count() > 0) {
            session()->flash('error', 'Нельзя удалить стадион, так как на нем запланированы матчи');
            return;
        }

        // Проверяем, есть ли связанные клубы
        if ($stadium->clubs()->count() > 0) {
            session()->flash('error', 'Нельзя удалить стадион, так как с ним связаны клубы');
            return;
        }

        // Delete associated media
        $stadium->media()->delete();

        $stadium->delete();

        session()->flash('message', 'Стадион успешно удален');
    }

    public function toggleStadiumStatus($stadiumId)
    {
        $this->authorize('manage-stadiums');

        $stadium = Stadium::findOrFail($stadiumId);
        $stadium->is_active = !$stadium->is_active;
        $stadium->save();

        session()->flash('message', 'Статус стадиона изменен');
    }

    public function removeCurrentImage()
    {
        $this->authorize('manage-stadiums');

        $stadium = Stadium::findOrFail($this->editingStadiumId);
        $stadium->clearMediaCollection('image');

        // Clear image_url field
        $stadium->update(['image_url' => null]);

        session()->flash('message', 'Изображение стадиона удалено');
    }

    public function render()
    {
        return view('livewire.stadium-management', [
            'stadiums' => $this->getStadiums(),
        ])->layout(get_user_layout());
    }
}
