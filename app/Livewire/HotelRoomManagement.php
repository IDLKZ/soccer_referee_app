<?php

namespace App\Livewire;

use App\Models\HotelRoom;
use App\Models\Hotel;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

#[Title('Управление номерами отелей')]
class HotelRoomManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingHotelRoomId = null;

    // Поиск и фильтрация
    public $search = '';
    public $filterHotel = '';
    public $filterBeds = '';
    public $filterAC = '';
    public $filterWiFi = '';

    protected $paginationTheme = 'tailwind';

    public $hotels = [];
    public $selectedMedia = [];

    // Для создания нового номера
    #[Validate('required|exists:hotels,id')]
    public $hotelId = '';

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

    #[Validate('required|integer|min:1')]
    public $bedQuantity = '';

    #[Validate('required|numeric|min:5')]
    public $roomSize = '';

    #[Validate('boolean')]
    public $airConditioning = false;

    #[Validate('boolean')]
    public $privateBathroom = false;

    #[Validate('boolean')]
    public $tv = false;

    #[Validate('boolean')]
    public $wifi = false;

    #[Validate('boolean')]
    public $smokingAllowed = false;

    // Для редактирования
    #[Validate('nullable|image|max:5120')] // max 5MB
    public $editImage = null;

    #[Validate('required|exists:hotels,id')]
    public $editHotelId = '';

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

    #[Validate('required|integer|min:1')]
    public $editBedQuantity = '';

    #[Validate('required|numeric|min:5')]
    public $editRoomSize = '';

    #[Validate('boolean')]
    public $editAirConditioning = false;

    #[Validate('boolean')]
    public $editPrivateBathroom = false;

    #[Validate('boolean')]
    public $editTv = false;

    #[Validate('boolean')]
    public $editWifi = false;

    #[Validate('boolean')]
    public $editSmokingAllowed = false;

    #[Locked]
    public $canCreate = false;

    #[Locked]
    public $canEdit = false;

    #[Locked]
    public $canDelete = false;

    public function mount()
    {
        $this->authorize('manage-hotel-rooms');

        $user = auth()->user();
        $this->canCreate = $user->can('create-hotel-rooms');
        $this->canEdit = $user->can('manage-hotel-rooms');
        $this->canDelete = $user->can('delete-hotel-rooms');

        $this->loadHotels();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterHotel()
    {
        $this->resetPage();
    }

    public function updatedFilterBeds()
    {
        $this->resetPage();
    }

    public function updatedFilterAC()
    {
        $this->resetPage();
    }

    public function updatedFilterWiFi()
    {
        $this->resetPage();
    }

    public function getHotelRooms()
    {
        $query = HotelRoom::with(['hotel']);

        // Поиск по названию
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('title_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('title_en', 'like', '%' . $this->search . '%')
                  ->orWhere('description_ru', 'like', '%' . $this->search . '%')
                  ->orWhere('description_kk', 'like', '%' . $this->search . '%')
                  ->orWhere('description_en', 'like', '%' . $this->search . '%');
            });
        }

        // Фильтр по отелю
        if (!empty($this->filterHotel)) {
            $query->where('hotel_id', $this->filterHotel);
        }

        // Фильтр по количеству спален
        if (!empty($this->filterBeds)) {
            $query->where('bed_quantity', $this->filterBeds);
        }

        // Фильтр по кондиционеру
        if ($this->filterAC !== '') {
            $query->where('air_conditioning', $this->filterAC);
        }

        // Фильтр по WiFi
        if ($this->filterWiFi !== '') {
            $query->where('wifi', $this->filterWiFi);
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function loadHotels()
    {
        $this->hotels = Hotel::where('is_active', true)
            ->orderBy('title_ru')
            ->get();
    }

    public function createHotelRoom()
    {
        $this->authorize('create-hotel-rooms');

        $this->validateCreateRules();

        $hotelRoom = HotelRoom::create([
            'hotel_id' => $this->hotelId,
            'title_ru' => $this->titleRu,
            'title_kk' => $this->titleKk,
            'title_en' => $this->titleEn,
            'description_ru' => $this->descriptionRu,
            'description_kk' => $this->descriptionKk,
            'description_en' => $this->descriptionEn,
            'bed_quantity' => $this->bedQuantity,
            'room_size' => $this->roomSize,
            'air_conditioning' => (bool) $this->airConditioning,
            'private_bathroom' => (bool) $this->privateBathroom,
            'tv' => (bool) $this->tv,
            'wifi' => (bool) $this->wifi,
            'smoking_allowed' => (bool) $this->smokingAllowed,
        ]);

        // Handle image upload
        if ($this->image) {
            $media = $hotelRoom->addMedia($this->image->getRealPath())
                  ->usingName($this->image->getClientOriginalName())
                  ->usingFileName($this->image->getClientOriginalName())
                  ->toMediaCollection('image');

            // Update image_url field with relative path
            $hotelRoom->update(['image_url' => $media->getUrl()]);
        }

        // Add selected media if any
        if (!empty($this->selectedMedia)) {
            foreach ($this->selectedMedia as $mediaId) {
                $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($mediaId);
                if ($media) {
                    $hotelRoom->addMediaFromMedia($media)
                          ->toMediaCollection('image');
                }
            }
        }

        $this->reset(['selectedMedia', 'hotelId', 'titleRu', 'titleKk', 'titleEn', 'descriptionRu', 'descriptionKk', 'descriptionEn', 'bedQuantity', 'roomSize', 'airConditioning', 'privateBathroom', 'tv', 'wifi', 'smokingAllowed', 'showCreateModal', 'image']);

        session()->flash('message', 'Номер успешно создан');

        // Перерисовываем компонент
        $this->render();
    }

    public function editHotelRoom($hotelRoomId)
    {
        $hotelRoom = HotelRoom::findOrFail($hotelRoomId);
        $this->authorize('manage-hotel-rooms');

        $this->editingHotelRoomId = $hotelRoom->id;
        $this->selectedMedia = []; // For media selection
        $this->editHotelId = $hotelRoom->hotel_id;
        $this->editTitleRu = $hotelRoom->title_ru;
        $this->editTitleKk = $hotelRoom->title_kk;
        $this->editTitleEn = $hotelRoom->title_en;
        $this->editDescriptionRu = $hotelRoom->description_ru;
        $this->editDescriptionKk = $hotelRoom->description_kk;
        $this->editDescriptionEn = $hotelRoom->description_en;
        $this->editBedQuantity = $hotelRoom->bed_quantity;
        $this->editRoomSize = $hotelRoom->room_size;
        $this->editAirConditioning = $hotelRoom->air_conditioning;
        $this->editPrivateBathroom = $hotelRoom->private_bathroom;
        $this->editTv = $hotelRoom->tv;
        $this->editWifi = $hotelRoom->wifi;
        $this->editSmokingAllowed = $hotelRoom->smoking_allowed;

        $this->showEditModal = true;
    }

    public function updateHotelRoom()
    {
        $this->authorize('manage-hotel-rooms');

        $hotelRoom = HotelRoom::findOrFail($this->editingHotelRoomId);

        $this->validateEditRules();

        $hotelRoom->update([
            'hotel_id' => $this->editHotelId,
            'title_ru' => $this->editTitleRu,
            'title_kk' => $this->editTitleKk,
            'title_en' => $this->editTitleEn,
            'description_ru' => $this->editDescriptionRu,
            'description_kk' => $this->editDescriptionKk,
            'description_en' => $this->editDescriptionEn,
            'bed_quantity' => $this->editBedQuantity,
            'room_size' => $this->editRoomSize,
            'air_conditioning' => (bool) $this->editAirConditioning,
            'private_bathroom' => (bool) $this->editPrivateBathroom,
            'tv' => (bool) $this->editTv,
            'wifi' => (bool) $this->editWifi,
            'smoking_allowed' => (bool) $this->editSmokingAllowed,
        ]);

        // Handle image upload
        if ($this->editImage) {
            // Clear existing images and add new one
            $hotelRoom->clearMediaCollection('image');
            $media = $hotelRoom->addMedia($this->editImage->getRealPath())
                  ->usingName($this->editImage->getClientOriginalName())
                  ->usingFileName($this->editImage->getClientOriginalName())
                  ->toMediaCollection('image');

            // Update image_url field with relative path
            $hotelRoom->update(['image_url' => $media->getUrl()]);
        }

        // Update image if new media is selected
        if (!empty($this->selectedMedia)) {
            $hotelRoom->clearMediaCollection('image');
            foreach ($this->selectedMedia as $mediaId) {
                $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($mediaId);
                if ($media) {
                    $hotelRoom->addMediaFromMedia($media)
                          ->toMediaCollection('image');
                }
            }
        }

        $this->reset(['selectedMedia', 'editHotelId', 'editTitleRu', 'editTitleKk', 'editTitleEn', 'editDescriptionRu', 'editDescriptionKk', 'editDescriptionEn', 'editBedQuantity', 'editRoomSize', 'editAirConditioning', 'editPrivateBathroom', 'editTv', 'editWifi', 'editSmokingAllowed', 'showEditModal', 'editingHotelRoomId', 'editImage']);

        session()->flash('message', 'Номер успешно обновлен');

        // Перерисовываем компонент
        $this->render();
    }

    public function deleteHotelRoom($hotelRoomId)
    {
        $this->authorize('delete-hotel-rooms');

        $hotelRoom = HotelRoom::findOrFail($hotelRoomId);

        // Delete associated media
        $hotelRoom->media()->delete();

        $hotelRoom->delete();

        session()->flash('message', 'Номер успешно удален');
    }

    public function removeCurrentImage()
    {
        $this->authorize('manage-hotel-rooms');

        $hotelRoom = HotelRoom::findOrFail($this->editingHotelRoomId);
        $hotelRoom->clearMediaCollection('image');

        // Clear image_url field
        $hotelRoom->update(['image_url' => null]);

        session()->flash('message', 'Изображение номера удалено');
    }

    protected function validateCreateRules()
    {
        return $this->validate([
            'hotelId' => 'required|exists:hotels,id',
            'titleRu' => 'required|string|max:255',
            'titleKk' => 'nullable|string|max:255',
            'titleEn' => 'nullable|string|max:255',
            'descriptionRu' => 'nullable|string',
            'descriptionKk' => 'nullable|string',
            'descriptionEn' => 'nullable|string',
            'bedQuantity' => 'required|integer|min:1',
            'roomSize' => 'required|numeric|min:5',
            'airConditioning' => 'boolean',
            'privateBathroom' => 'boolean',
            'tv' => 'boolean',
            'wifi' => 'boolean',
            'smokingAllowed' => 'boolean',
            'image' => 'nullable|image|max:5120',
        ]);
    }

    protected function validateEditRules()
    {
        return $this->validate([
            'editHotelId' => 'required|exists:hotels,id',
            'editTitleRu' => 'required|string|max:255',
            'editTitleKk' => 'nullable|string|max:255',
            'editTitleEn' => 'nullable|string|max:255',
            'editDescriptionRu' => 'nullable|string',
            'editDescriptionKk' => 'nullable|string',
            'editDescriptionEn' => 'nullable|string',
            'editBedQuantity' => 'required|integer|min:1',
            'editRoomSize' => 'required|numeric|min:5',
            'editAirConditioning' => 'boolean',
            'editPrivateBathroom' => 'boolean',
            'editTv' => 'boolean',
            'editWifi' => 'boolean',
            'editSmokingAllowed' => 'boolean',
            'editImage' => 'nullable|image|max:5120',
        ]);
    }

    public function render()
    {
        return view('livewire.hotel-room-management', [
            'hotelRooms' => $this->getHotelRooms(),
        ])->layout(get_user_layout());
    }
}