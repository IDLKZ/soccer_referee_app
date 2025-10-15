<?php

namespace App\Livewire;

use App\Models\Hotel;
use App\Models\City;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

#[Title('Управление отелями')]
#[Layout('layouts.admin')]
class HotelManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingHotelId = null;

    // Поиск и фильтрация
    public $search = '';
    public $filterCity = '';
    public $filterStar = '';
    public $filterStatus = '';

    protected $paginationTheme = 'tailwind';

    public $cities = [];
    public $selectedMedia = [];

    // Для создания нового отеля
    #[Validate('required|exists:cities,id')]
    public $cityId = '';

    #[Validate('nullable|image|max:5120')] // max 5MB
    public $image = null;

    #[Validate('required|string|max:255')]
    public $titleRu = '';

    #[Validate('nullable|string|max:255')]
    public $titleKk = '';

    #[Validate('nullable|string|max:255')]
    public $titleEn = '';

    #[Validate('nullable|string')]
    public $descriptionRu = '';

    #[Validate('nullable|string')]
    public $descriptionKk = '';

    #[Validate('nullable|string')]
    public $descriptionEn = '';

    #[Validate('required|integer|min:1|max:5')]
    public $star = '';

    #[Validate('nullable|email|max:255')]
    public $email = '';

    #[Validate('nullable|string|max:500')]
    public $addressRu = '';

    #[Validate('nullable|string|max:500')]
    public $addressKk = '';

    #[Validate('nullable|string|max:500')]
    public $addressEn = '';

    #[Validate('nullable|string|max:500')]
    public $websiteRu = '';

    #[Validate('nullable|string|max:500')]
    public $websiteKk = '';

    #[Validate('nullable|string|max:500')]
    public $websiteEn = '';

    #[Validate('nullable|numeric|between:-90,90')]
    public $lat = '';

    #[Validate('nullable|numeric|between:-180,180')]
    public $lon = '';

    #[Validate('boolean')]
    public $isActive = true;

    #[Validate('boolean')]
    public $isPartner = false;

    // Для редактирования
    #[Validate('nullable|image|max:5120')] // max 5MB
    public $editImage = null;

    #[Validate('required|exists:cities,id')]
    public $editCityId = '';

    #[Validate('required|string|max:255')]
    public $editTitleRu = '';

    #[Validate('nullable|string|max:255')]
    public $editTitleKk = '';

    #[Validate('nullable|string|max:255')]
    public $editTitleEn = '';

    #[Validate('nullable|string')]
    public $editDescriptionRu = '';

    #[Validate('nullable|string')]
    public $editDescriptionKk = '';

    #[Validate('nullable|string')]
    public $editDescriptionEn = '';

    #[Validate('required|integer|min:1|max:5')]
    public $editStar = '';

    #[Validate('nullable|email|max:255')]
    public $editEmail = '';

    #[Validate('nullable|string|max:500')]
    public $editAddressRu = '';

    #[Validate('nullable|string|max:500')]
    public $editAddressKk = '';

    #[Validate('nullable|string|max:500')]
    public $editAddressEn = '';

    #[Validate('nullable|string|max:500')]
    public $editWebsiteRu = '';

    #[Validate('nullable|string|max:500')]
    public $editWebsiteKk = '';

    #[Validate('nullable|string|max:500')]
    public $editWebsiteEn = '';

    #[Validate('nullable|numeric|between:-90,90')]
    public $editLat = '';

    #[Validate('nullable|numeric|between:-180,180')]
    public $editLon = '';

    #[Validate('boolean')]
    public $editIsActive = true;

    #[Validate('boolean')]
    public $editIsPartner = false;

    
    #[Locked]
    public $canCreate = false;

    #[Locked]
    public $canEdit = false;

    #[Locked]
    public $canDelete = false;

    public function mount()
    {
        $this->authorize('manage-hotels');

        $user = auth()->user();
        $this->canCreate = $user->can('create-hotels');
        $this->canEdit = $user->can('manage-hotels');
        $this->canDelete = $user->can('delete-hotels');

        $this->loadCities();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterCity()
    {
        $this->resetPage();
    }

    public function updatedFilterStar()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function getHotels()
    {
        $query = Hotel::with(['city']);

        // Поиск по названию
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('title_en', 'like', '%' . $this->search . '%')
                  ->orWhere('address_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('address_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('address_en', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Фильтр по городу
        if (!empty($this->filterCity)) {
            $query->where('city_id', $this->filterCity);
        }

        // Фильтр по количеству звезд
        if (!empty($this->filterStar)) {
            $query->where('star', $this->filterStar);
        }

        // Фильтр по статусу
        if ($this->filterStatus !== '') {
            $query->where('is_active', $this->filterStatus);
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function loadCities()
    {
        $this->cities = City::where('is_active', true)
            ->orderBy('title_ru')
            ->get();
    }

    public function createHotel()
    {
        $this->authorize('create-hotels');

        $this->validate([
            'cityId' => 'required|exists:cities,id',
            'titleRu' => 'required|string|max:255',
            'titleKk' => 'nullable|string|max:255',
            'titleEn' => 'nullable|string|max:255',
            'descriptionRu' => 'nullable|string',
            'descriptionKk' => 'nullable|string',
            'descriptionEn' => 'nullable|string',
            'star' => 'required|integer|min:1|max:5',
            'email' => 'nullable|email|max:255',
            'addressRu' => 'nullable|string|max:500',
            'addressKk' => 'nullable|string|max:500',
            'addressEn' => 'nullable|string|max:500',
            'websiteRu' => 'nullable|string|max:500',
            'websiteKk' => 'nullable|string|max:500',
            'websiteEn' => 'nullable|string|max:500',
            'lat' => 'nullable|numeric|between:-90,90',
            'lon' => 'nullable|numeric|between:-180,180',
            'image' => 'nullable|image|max:5120',
            'isActive' => 'boolean',
            'isPartner' => 'boolean',
        ]);

        $hotel = Hotel::create([
            'city_id' => $this->cityId,
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
            'description_ru' => $this->descriptionRu,
            'description_kk' => $this->descriptionKk,
            'description_en' => $this->descriptionEn,
            'star' => $this->star,
            'email' => $this->email,
            'address_ru' => $this->addressRu,
            'address_kk' => $this->addressKk,
            'address_en' => $this->addressEn,
            'website_ru' => $this->websiteRu,
            'website_kk' => $this->websiteKk,
            'website_en' => $this->websiteEn,
            'lat' => $this->lat ?: null,
            'lon' => $this->lon ?: null,
            'is_active' => (bool) $this->isActive,
            'is_partner' => (bool) $this->isPartner,
        ]);

        // Handle image upload
        if ($this->image) {
            $media = $hotel->addMedia($this->image->getRealPath())
                  ->usingName($this->image->getClientOriginalName())
                  ->usingFileName($this->image->getClientOriginalName())
                  ->toMediaCollection('image');

            // Update image_url field with relative path
            $hotel->update(['image_url' => $media->getUrl()]);
        }

        // Add selected media if any
        if (!empty($this->selectedMedia)) {
            foreach ($this->selectedMedia as $mediaId) {
                $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($mediaId);
                if ($media) {
                    $hotel->addMediaFromMedia($media)
                          ->toMediaCollection('image');
                }
            }
        }

        $this->reset(['selectedMedia', 'cityId', 'titleRu', 'titleKk', 'titleEn', 'descriptionRu', 'descriptionKk', 'descriptionEn', 'star', 'email', 'addressRu', 'addressKk', 'addressEn', 'websiteRu', 'websiteKk', 'websiteEn', 'lat', 'lon', 'isActive', 'isPartner', 'showCreateModal', 'image']);

        session()->flash('message', 'Отель успешно создан');

        // Перерисовываем компонент
        $this->render();
    }

    public function editHotel($hotelId)
    {
        $hotel = Hotel::findOrFail($hotelId);
        $this->authorize('manage-hotels');

        $this->editingHotelId = $hotel->id;
        $this->selectedMedia = []; // For media selection
        $this->editCityId = $hotel->city_id;
        $this->editTitleRu = $hotel->title_ru;
        $this->editTitleKk = $hotel->title_kk;
        $this->editTitleEn = $hotel->title_en;
        $this->editDescriptionRu = $hotel->description_ru;
        $this->editDescriptionKk = $hotel->description_kk;
        $this->editDescriptionEn = $hotel->description_en;
        $this->editStar = $hotel->star;
        $this->editEmail = $hotel->email;
        $this->editAddressRu = $hotel->address_ru;
        $this->editAddressKk = $hotel->address_kk;
        $this->editAddressEn = $hotel->address_en;
        $this->editWebsiteRu = $hotel->website_ru;
        $this->editWebsiteKk = $hotel->website_kk;
        $this->editWebsiteEn = $hotel->website_en;
        $this->editLat = $hotel->lat;
        $this->editLon = $hotel->lon;
        $this->editIsActive = $hotel->is_active;
        $this->editIsPartner = $hotel->is_partner;

        $this->showEditModal = true;
    }

    public function updateHotel()
    {
        $this->authorize('manage-hotels');

        $hotel = Hotel::findOrFail($this->editingHotelId);

        $this->validate([
            'editCityId' => 'required|exists:cities,id',
            'editTitleRu' => 'required|string|max:255',
            'editTitleKk' => 'nullable|string|max:255',
            'editTitleEn' => 'nullable|string|max:255',
            'editDescriptionRu' => 'nullable|string',
            'editDescriptionKk' => 'nullable|string',
            'editDescriptionEn' => 'nullable|string',
            'editStar' => 'required|integer|min:1|max:5',
            'editEmail' => 'nullable|email|max:255',
            'editAddressRu' => 'nullable|string|max:500',
            'editAddressKk' => 'nullable|string|max:500',
            'editAddressEn' => 'nullable|string|max:500',
            'editWebsiteRu' => 'nullable|string|max:500',
            'editWebsiteKk' => 'nullable|string|max:500',
            'editWebsiteEn' => 'nullable|string|max:500',
            'editLat' => 'nullable|numeric|between:-90,90',
            'editLon' => 'nullable|numeric|between:-180,180',
            'editImage' => 'nullable|image|max:5120',
            'editIsActive' => 'boolean',
            'editIsPartner' => 'boolean',
        ]);

        $hotel->update([
            'city_id' => $this->editCityId,
            'title_ru' => $this->editTitleRu,
            'title_kk' => $this->editTitleKk,
            'title_en' => $this->editTitleEn,
            'description_ru' => $this->editDescriptionRu,
            'description_kk' => $this->editDescriptionKk,
            'description_en' => $this->editDescriptionEn,
            'star' => $this->editStar,
            'email' => $this->editEmail,
            'address_ru' => $this->editAddressRu,
            'address_kk' => $this->editAddressKk,
            'address_en' => $this->editAddressEn,
            'website_ru' => $this->editWebsiteRu,
            'website_kk' => $this->editWebsiteKk,
            'website_en' => $this->editWebsiteEn,
            'lat' => $this->editLat ?: null,
            'lon' => $this->editLon ?: null,
            'is_active' => (bool) $this->editIsActive,
            'is_partner' => (bool) $this->editIsPartner,
        ]);

        // Handle image upload
        if ($this->editImage) {
            // Clear existing images and add new one
            $hotel->clearMediaCollection('image');
            $media = $hotel->addMedia($this->editImage->getRealPath())
                  ->usingName($this->editImage->getClientOriginalName())
                  ->usingFileName($this->editImage->getClientOriginalName())
                  ->toMediaCollection('image');

            // Update image_url field with relative path
            $hotel->update(['image_url' => $media->getUrl()]);
        }

        // Update image if new media is selected
        if (!empty($this->selectedMedia)) {
            $hotel->clearMediaCollection('image');
            foreach ($this->selectedMedia as $mediaId) {
                $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($mediaId);
                if ($media) {
                    $hotel->addMediaFromMedia($media)
                          ->toMediaCollection('image');
                }
            }
        }

        $this->reset(['selectedMedia', 'editCityId', 'editTitleRu', 'editTitleKk', 'editTitleEn', 'editDescriptionRu', 'editDescriptionKk', 'editDescriptionEn', 'editStar', 'editEmail', 'editAddressRu', 'editAddressKk', 'editAddressEn', 'editWebsiteRu', 'editWebsiteKk', 'editWebsiteEn', 'editLat', 'editLon', 'editIsActive', 'editIsPartner', 'showEditModal', 'editingHotelId', 'editImage']);

        session()->flash('message', 'Отель успешно обновлен');

        // Перерисовываем компонент
        $this->render();
    }

    public function deleteHotel($hotelId)
    {
        $this->authorize('delete-hotels');

        $hotel = Hotel::findOrFail($hotelId);

        // Проверяем, есть ли связанные номера
        if ($hotel->hotel_rooms()->count() > 0) {
            session()->flash('error', 'Нельзя удалить отель, так как у него есть номера');
            return;
        }

        // Проверяем, есть ли связанные поездки
        if ($hotel->trips()->count() > 0) {
            session()->flash('error', 'Нельзя удалить отель, так как с ним связаны поездки');
            return;
        }

        // Delete associated media
        $hotel->media()->delete();

        $hotel->delete();

        session()->flash('message', 'Отель успешно удален');
    }

    public function removeCurrentImage()
    {
        $this->authorize('manage-hotels');

        $hotel = Hotel::findOrFail($this->editingHotelId);
        $hotel->clearMediaCollection('image');

        // Clear image_url field
        $hotel->update(['image_url' => null]);

        session()->flash('message', 'Изображение отеля удалено');
    }

    public function render()
    {
        return view('livewire.hotel-management', [
            'hotels' => $this->getHotels(),
        ]);
    }
}