<?php

namespace App\Livewire;

use App\Models\RoomFacility;
use App\Models\HotelRoom;
use App\Models\Facility;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

#[Title('Управление удобствами номеров')]
class RoomFacilityManagement extends Component
{
    use WithPagination;

    public $showCreateModal = false;
    public $showEditModal = false;
    public $editingRoomFacilityId = null;

    // Поиск
    public $search = '';
    public $searchRoom = '';
    public $searchFacility = '';

    protected $paginationTheme = 'tailwind';

    // Для создания новой связи
    #[Validate('required|exists:hotel_rooms,id')]
    public $roomId = '';

    #[Validate('required|exists:facilities,id')]
    public $facilityId = '';

    // Для редактирования
    #[Validate('required|exists:hotel_rooms,id')]
    public $editRoomId = '';

    #[Validate('required|exists:facilities,id')]
    public $editFacilityId = '';

    #[Locked]
    public $canCreate = false;

    #[Locked]
    public $canEdit = false;

    #[Locked]
    public $canDelete = false;

    public function mount()
    {
        $this->authorize('manage-room-facilities');

        $user = auth()->user();
        $this->canCreate = $user->can('create-room-facilities');
        $this->canEdit = $user->can('manage-room-facilities');
        $this->canDelete = $user->can('delete-room-facilities');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSearchRoom()
    {
        $this->resetPage();
    }

    public function updatedSearchFacility()
    {
        $this->resetPage();
    }

    public function getRoomFacilities()
    {
        $query = RoomFacility::with(['hotel_room', 'facility']);

        // Поиск по названию номера
        if ($this->searchRoom) {
            $query->whereHas('hotel_room', function($q) {
                $q->where('title_ru', 'like', '%' . $this->searchRoom . '%')
                  ->orWhere('title_kk', 'like', '%' . $this->searchRoom . '%')
                  ->orWhere('title_en', 'like', '%' . $this->searchRoom . '%');
            });
        }

        // Поиск по названию удобства
        if ($this->searchFacility) {
            $query->whereHas('facility', function($q) {
                $q->where('title_ru', 'like', '%' . $this->searchFacility . '%')
                  ->orWhere('title_kk', 'like', '%' . $this->searchFacility . '%')
                  ->orWhere('title_en', 'like', '%' . $this->searchFacility . '%');
            });
        }

        return $query->orderBy('id', 'desc')->paginate(10);
    }

    public function getRooms()
    {
        return HotelRoom::orderBy('title_ru', 'asc')->get();
    }

    public function getFacilities()
    {
        return Facility::orderBy('title_ru', 'asc')->get();
    }

    public function createRoomFacility()
    {
        $this->authorize('create-room-facilities');

        $this->validate([
            'roomId' => 'required|exists:hotel_rooms,id',
            'facilityId' => 'required|exists:facilities,id',
        ]);

        // Проверяем, что такая связь еще не существует
        $exists = RoomFacility::where('room_id', $this->roomId)
                              ->where('facility_id', $this->facilityId)
                              ->exists();

        if ($exists) {
            $this->addError('duplicate', 'Такая связь между номером и удобством уже существует');
            return;
        }

        RoomFacility::create([
            'room_id' => $this->roomId,
            'facility_id' => $this->facilityId,
        ]);

        $this->reset(['roomId', 'facilityId', 'showCreateModal']);

        session()->flash('message', 'Связь успешно создана');
    }

    public function editRoomFacility($roomFacilityId)
    {
        $roomFacility = RoomFacility::findOrFail($roomFacilityId);
        $this->authorize('manage-room-facilities');

        $this->editingRoomFacilityId = $roomFacility->id;
        $this->editRoomId = $roomFacility->room_id;
        $this->editFacilityId = $roomFacility->facility_id;

        $this->showEditModal = true;
    }

    public function updateRoomFacility()
    {
        $this->authorize('manage-room-facilities');

        $roomFacility = RoomFacility::findOrFail($this->editingRoomFacilityId);

        $this->validate([
            'editRoomId' => 'required|exists:hotel_rooms,id',
            'editFacilityId' => 'required|exists:facilities,id',
        ]);

        // Проверяем, что такая связь еще не существует (исключая текущую запись)
        $exists = RoomFacility::where('room_id', $this->editRoomId)
                              ->where('facility_id', $this->editFacilityId)
                              ->where('id', '!=', $this->editingRoomFacilityId)
                              ->exists();

        if ($exists) {
            $this->addError('duplicate_edit', 'Такая связь между номером и удобством уже существует');
            return;
        }

        $roomFacility->update([
            'room_id' => $this->editRoomId,
            'facility_id' => $this->editFacilityId,
        ]);

        $this->reset(['editRoomId', 'editFacilityId', 'showEditModal', 'editingRoomFacilityId']);

        session()->flash('message', 'Связь успешно обновлена');

        // Перерисовываем компонент
        $this->render();
    }

    public function deleteRoomFacility($roomFacilityId)
    {
        $this->authorize('delete-room-facilities');

        $roomFacility = RoomFacility::findOrFail($roomFacilityId);

        $roomFacility->delete();

        session()->flash('message', 'Связь успешно удалена');
    }

    public function render()
    {
        return view('livewire.room-facility-management', [
            'roomFacilities' => $this->getRoomFacilities(),
            'rooms' => $this->getRooms(),
            'facilities' => $this->getFacilities(),
        ])->layout(get_user_layout());
    }
}