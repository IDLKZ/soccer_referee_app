<?php

namespace App\Livewire;

use App\Models\MatchEntity;
use App\Models\Trip;
use App\Models\TripHotel;
use App\Models\HotelRoom;
use App\Models\Hotel;
use App\Models\TripMigration;
use App\Models\TripDocument;
use App\Models\City;
use App\Models\TransportType;
use App\Models\Operation;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;

#[Title('Детали командировки')]
class BusinessTripDetail extends Component
{
    use WithFileUploads;
    public $matchId;
    public $match;
    public $trips = [];

    // Hotels Modal
    public $showHotelsModal = false;
    public $currentTripId = null;
    public $currentTrip = null;
    public $tripHotels = [];
    public $hotels = [];
    public $hotelRooms = [];

    // Hotel Form
    public $hotelForm = [
        'id' => null,
        'hotel_id' => '',
        'hotel_room_id' => '',
        'check_in_at' => '',
        'check_out_at' => '',
    ];

    // Migrations Modal
    public $showMigrationsModal = false;
    public $tripMigrations = [];
    public $cities = [];
    public $transportTypes = [];

    // Migration Form
    public $migrationForm = [
        'id' => null,
        'transport_type_id' => '',
        'departure_city_id' => '',
        'arrival_city_id' => '',
        'from_date' => '',
        'to_date' => '',
        'info' => '',
    ];

    // Documents Modal
    public $showDocumentsModal = false;
    public $tripDocuments = [];
    public $documentFile = null;

    // Document Form
    public $documentForm = [
        'id' => null,
        'title' => '',
        'price' => 0,
        'qty' => 1,
        'info' => '',
        'is_active' => true,
    ];

    public function mount($id)
    {
        $this->authorize('manage-logistics');
        $this->matchId = $id;
        $this->loadMatch();
        $this->loadHotels();
        $this->loadCities();
        $this->loadTransportTypes();
    }

    public function loadMatch()
    {
        $this->match = MatchEntity::with([
            'league', 'season', 'stadium.city', 'operation',
            'ownerClub', 'guestClub',
            'trips.judge', 'trips.operation', 'trips.judge.role',
            'trips.city', 'trips.transport_type',
            'trips.trip_hotels.hotel_room.hotel',
            'trips.trip_migrations.transport_type',
            'trips.trip_documents'
        ])->findOrFail($this->matchId);

        $this->trips = $this->match->trips;
    }

    public function loadHotels()
    {
        $this->hotels = Hotel::orderBy('title_ru')->get();
    }

    public function loadHotelRooms($hotelId)
    {
        $this->hotelRooms = HotelRoom::where('hotel_id', $hotelId)
            ->with('hotel')
            ->get();
    }

    public function updatedHotelFormHotelId($value)
    {
        $this->hotelForm['hotel_room_id'] = '';
        if ($value) {
            $this->loadHotelRooms($value);
        } else {
            $this->hotelRooms = [];
        }
    }

    public function openHotelsModal($tripId)
    {
        $this->currentTripId = $tripId;
        $this->currentTrip = Trip::with([
            'trip_hotels.hotel_room.hotel',
            'user',
            'operation'
        ])->findOrFail($tripId);

        $this->tripHotels = $this->currentTrip->trip_hotels;
        $this->showHotelsModal = true;
        $this->resetHotelForm();
    }

    public function closeHotelsModal()
    {
        $this->showHotelsModal = false;
        $this->currentTripId = null;
        $this->currentTrip = null;
        $this->tripHotels = [];
        $this->resetHotelForm();
    }

    public function resetHotelForm()
    {
        $this->hotelForm = [
            'id' => null,
            'hotel_id' => '',
            'hotel_room_id' => '',
            'check_in_at' => '',
            'check_out_at' => '',
        ];
        $this->hotelRooms = [];
    }

    public function editHotel($hotelId)
    {
        $tripHotel = TripHotel::with('hotel_room.hotel')->findOrFail($hotelId);

        $this->hotelForm = [
            'id' => $tripHotel->id,
            'hotel_id' => $tripHotel->hotel_id,
            'hotel_room_id' => $tripHotel->room_id,
            'check_in_at' => $tripHotel->from_date ? \Carbon\Carbon::parse($tripHotel->from_date)->format('Y-m-d\TH:i') : '',
            'check_out_at' => $tripHotel->to_date ? \Carbon\Carbon::parse($tripHotel->to_date)->format('Y-m-d\TH:i') : '',
        ];

        $this->loadHotelRooms($tripHotel->hotel_id);
    }

    public function saveHotel()
    {
        $this->validate([
            'hotelForm.hotel_id' => 'required|exists:hotels,id',
            'hotelForm.hotel_room_id' => 'required|exists:hotel_rooms,id',
            'hotelForm.check_in_at' => 'required|date',
            'hotelForm.check_out_at' => 'required|date|after:hotelForm.check_in_at',
        ], [
            'hotelForm.hotel_id.required' => 'Выберите отель',
            'hotelForm.hotel_room_id.required' => 'Выберите номер',
            'hotelForm.check_in_at.required' => 'Укажите дату заезда',
            'hotelForm.check_out_at.required' => 'Укажите дату выезда',
            'hotelForm.check_out_at.after' => 'Дата выезда должна быть после даты заезда',
        ]);

        if ($this->hotelForm['id']) {
            // Update
            $tripHotel = TripHotel::findOrFail($this->hotelForm['id']);
            $tripHotel->update([
                'hotel_id' => $this->hotelForm['hotel_id'],
                'room_id' => $this->hotelForm['hotel_room_id'],
                'from_date' => $this->hotelForm['check_in_at'],
                'to_date' => $this->hotelForm['check_out_at'],
            ]);

            session()->flash('message', 'Отель успешно обновлен');
        } else {
            // Create
            TripHotel::create([
                'trip_id' => $this->currentTripId,
                'hotel_id' => $this->hotelForm['hotel_id'],
                'room_id' => $this->hotelForm['hotel_room_id'],
                'from_date' => $this->hotelForm['check_in_at'],
                'to_date' => $this->hotelForm['check_out_at'],
            ]);

            session()->flash('message', 'Отель успешно добавлен');
        }

        $this->resetHotelForm();
        $this->loadMatch();
        $this->openHotelsModal($this->currentTripId);
    }

    public function deleteHotel($hotelId)
    {
        TripHotel::findOrFail($hotelId)->delete();

        session()->flash('message', 'Отель успешно удален');
        $this->loadMatch();
        $this->openHotelsModal($this->currentTripId);
    }

    public function loadCities()
    {
        $this->cities = City::orderBy('title_ru')->get();
    }

    public function loadTransportTypes()
    {
        $this->transportTypes = TransportType::orderBy('title_ru')->get();
    }

    public function openMigrationsModal($tripId)
    {
        $this->currentTripId = $tripId;
        $this->currentTrip = Trip::with([
            'trip_migrations.departure_city',
            'trip_migrations.arrival_city',
            'trip_migrations.transport_type',
            'user',
            'operation'
        ])->findOrFail($tripId);

        $this->tripMigrations = $this->currentTrip->trip_migrations;
        $this->showMigrationsModal = true;
        $this->resetMigrationForm();
    }

    public function closeMigrationsModal()
    {
        $this->showMigrationsModal = false;
        $this->currentTripId = null;
        $this->currentTrip = null;
        $this->tripMigrations = [];
        $this->resetMigrationForm();
    }

    public function resetMigrationForm()
    {
        $this->migrationForm = [
            'id' => null,
            'transport_type_id' => '',
            'departure_city_id' => '',
            'arrival_city_id' => '',
            'from_date' => '',
            'to_date' => '',
            'info' => '',
        ];
    }

    public function editMigration($migrationId)
    {
        $tripMigration = TripMigration::with('departure_city', 'arrival_city', 'transport_type')->findOrFail($migrationId);

        $this->migrationForm = [
            'id' => $tripMigration->id,
            'transport_type_id' => $tripMigration->transport_type_id,
            'departure_city_id' => $tripMigration->departure_city_id,
            'arrival_city_id' => $tripMigration->arrival_city_id,
            'from_date' => $tripMigration->from_date ? \Carbon\Carbon::parse($tripMigration->from_date)->format('Y-m-d\TH:i') : '',
            'to_date' => $tripMigration->to_date ? \Carbon\Carbon::parse($tripMigration->to_date)->format('Y-m-d\TH:i') : '',
            'info' => $tripMigration->info ?? '',
        ];
    }

    public function saveMigration()
    {
        $this->validate([
            'migrationForm.transport_type_id' => 'required|exists:transport_types,id',
            'migrationForm.departure_city_id' => 'required|exists:cities,id',
            'migrationForm.arrival_city_id' => 'required|exists:cities,id|different:migrationForm.departure_city_id',
            'migrationForm.from_date' => 'required|date',
            'migrationForm.to_date' => 'required|date|after:migrationForm.from_date',
        ], [
            'migrationForm.transport_type_id.required' => 'Выберите тип транспорта',
            'migrationForm.departure_city_id.required' => 'Выберите город отправления',
            'migrationForm.arrival_city_id.required' => 'Выберите город прибытия',
            'migrationForm.arrival_city_id.different' => 'Город прибытия должен отличаться от города отправления',
            'migrationForm.from_date.required' => 'Укажите дату отправления',
            'migrationForm.to_date.required' => 'Укажите дату прибытия',
            'migrationForm.to_date.after' => 'Дата прибытия должна быть после даты отправления',
        ]);

        if ($this->migrationForm['id']) {
            // Update
            $tripMigration = TripMigration::findOrFail($this->migrationForm['id']);
            $tripMigration->update([
                'transport_type_id' => $this->migrationForm['transport_type_id'],
                'departure_city_id' => $this->migrationForm['departure_city_id'],
                'arrival_city_id' => $this->migrationForm['arrival_city_id'],
                'from_date' => $this->migrationForm['from_date'],
                'to_date' => $this->migrationForm['to_date'],
                'info' => $this->migrationForm['info'],
            ]);

            session()->flash('message', 'Маршрут успешно обновлен');
        } else {
            // Create
            TripMigration::create([
                'trip_id' => $this->currentTripId,
                'transport_type_id' => $this->migrationForm['transport_type_id'],
                'departure_city_id' => $this->migrationForm['departure_city_id'],
                'arrival_city_id' => $this->migrationForm['arrival_city_id'],
                'from_date' => $this->migrationForm['from_date'],
                'to_date' => $this->migrationForm['to_date'],
                'info' => $this->migrationForm['info'],
            ]);

            session()->flash('message', 'Маршрут успешно добавлен');
        }

        $this->resetMigrationForm();
        $this->loadMatch();
        $this->openMigrationsModal($this->currentTripId);
    }

    public function deleteMigration($migrationId)
    {
        TripMigration::findOrFail($migrationId)->delete();

        session()->flash('message', 'Маршрут успешно удален');
        $this->loadMatch();
        $this->openMigrationsModal($this->currentTripId);
    }

    public function openDocumentsModal($tripId)
    {
        $this->currentTripId = $tripId;
        $this->currentTrip = Trip::with([
            'trip_documents',
            'user',
            'operation'
        ])->findOrFail($tripId);

        $this->tripDocuments = $this->currentTrip->trip_documents;
        $this->showDocumentsModal = true;
        $this->resetDocumentForm();
    }

    public function closeDocumentsModal()
    {
        $this->showDocumentsModal = false;
        $this->currentTripId = null;
        $this->currentTrip = null;
        $this->tripDocuments = [];
        $this->documentFile = null;
        $this->resetDocumentForm();
    }

    public function resetDocumentForm()
    {
        $this->documentForm = [
            'id' => null,
            'title' => '',
            'price' => 0,
            'qty' => 1,
            'info' => '',
            'is_active' => true,
        ];
        $this->documentFile = null;
    }

    public function editDocument($documentId)
    {
        $tripDocument = TripDocument::findOrFail($documentId);

        $this->documentForm = [
            'id' => $tripDocument->id,
            'title' => $tripDocument->title,
            'price' => $tripDocument->price,
            'qty' => $tripDocument->qty,
            'info' => $tripDocument->info ?? '',
            'is_active' => $tripDocument->is_active,
        ];
    }

    public function saveDocument()
    {
        $this->validate([
            'documentForm.title' => 'required|string|max:255',
            'documentForm.price' => 'required|numeric|min:0',
            'documentForm.qty' => 'required|numeric|min:0.01',
            'documentFile' => $this->documentForm['id'] ? 'nullable|file|max:10240' : 'nullable|file|max:10240',
        ], [
            'documentForm.title.required' => 'Укажите название документа',
            'documentForm.price.required' => 'Укажите цену',
            'documentForm.price.numeric' => 'Цена должна быть числом',
            'documentForm.qty.required' => 'Укажите количество',
            'documentForm.qty.numeric' => 'Количество должно быть числом',
            'documentFile.max' => 'Максимальный размер файла 10 МБ',
        ]);

        $totalPrice = $this->documentForm['price'] * $this->documentForm['qty'];
        $fileUrl = null;

        // Handle file upload
        if ($this->documentFile) {
            $fileUrl = $this->documentFile->store('trip-documents', 'public');
        }

        if ($this->documentForm['id']) {
            // Update
            $tripDocument = TripDocument::findOrFail($this->documentForm['id']);
            $updateData = [
                'title' => $this->documentForm['title'],
                'price' => $this->documentForm['price'],
                'qty' => $this->documentForm['qty'],
                'total_price' => $totalPrice,
                'info' => $this->documentForm['info'],
                'is_active' => $this->documentForm['is_active'],
            ];

            if ($fileUrl) {
                // Delete old file if exists
                if ($tripDocument->file_url && \Storage::disk('public')->exists($tripDocument->file_url)) {
                    \Storage::disk('public')->delete($tripDocument->file_url);
                }
                $updateData['file_url'] = $fileUrl;
            }

            $tripDocument->update($updateData);

            session()->flash('message', 'Документ успешно обновлен');
        } else {
            // Create
            TripDocument::create([
                'trip_id' => $this->currentTripId,
                'title' => $this->documentForm['title'],
                'price' => $this->documentForm['price'],
                'qty' => $this->documentForm['qty'],
                'total_price' => $totalPrice,
                'info' => $this->documentForm['info'],
                'is_active' => $this->documentForm['is_active'],
                'file_url' => $fileUrl,
            ]);

            session()->flash('message', 'Документ успешно добавлен');
        }

        $this->resetDocumentForm();
        $this->loadMatch();
        $this->openDocumentsModal($this->currentTripId);
    }

    public function deleteDocument($documentId)
    {
        $tripDocument = TripDocument::findOrFail($documentId);

        // Delete file if exists
        if ($tripDocument->file_url && \Storage::disk('public')->exists($tripDocument->file_url)) {
            \Storage::disk('public')->delete($tripDocument->file_url);
        }

        $tripDocument->delete();

        session()->flash('message', 'Документ успешно удален');
        $this->loadMatch();
        $this->openDocumentsModal($this->currentTripId);
    }

    public function toggleDocumentStatus($documentId)
    {
        $tripDocument = TripDocument::findOrFail($documentId);
        $tripDocument->update(['is_active' => !$tripDocument->is_active]);

        session()->flash('message', 'Статус документа изменен');
        $this->loadMatch();
        $this->openDocumentsModal($this->currentTripId);
    }

    /**
     * Determine the next operation value based on a single trip's statuses
     */
    public function determineNextOperationForTrip($trip)
    {
        if ($trip->judge_status == 0 && $trip->first_status == 0 && $trip->final_status == 0) {
            return 'referee_team_confirmation';
        } else if ($trip->judge_status == -1 && $trip->first_status == 0 && $trip->final_status == 0) {
            return 'referee_team_confirmation';
        } elseif ($trip->judge_status == 1 && $trip->first_status == 0) {
            return 'primary_business_trip_confirmation';
        } elseif ($trip->judge_status == 1 && $trip->first_status == 1) {
            return 'final_business_trip_confirmation';
        }

        throw new \Exception('Невозможно определить следующую операцию для командировки ID: ' . $trip->id);
    }

    /**
     * Submit a single trip for confirmation
     */
    public function submitForConfirmation($tripId)
    {
        try {
            $trip = Trip::with(['trip_hotels', 'trip_migrations', 'trip_documents'])->findOrFail($tripId);

            // Validation: Check if trip has at least one hotel OR migration
            $errors = [];

            if ($trip->trip_hotels->count() === 0 && $trip->trip_migrations->count() === 0) {
                $errors[] = 'Необходимо добавить хотя бы один отель или маршрут';
            }

            if (!empty($errors)) {
                session()->flash('error', implode('. ', $errors));
                return;
            }

            // Determine next operation for this specific trip
            $nextOperationValue = $this->determineNextOperationForTrip($trip);
            $nextOperation = Operation::where('value', $nextOperationValue)->first();

            if (!$nextOperation) {
                throw new \Exception("Операция '{$nextOperationValue}' не найдена в системе.");
            }

            // Update this trip's operation
            // Observer will automatically update match operation
            $trip->update(['operation_id' => $nextOperation->id]);

            session()->flash('message', 'Командировка успешно отправлена на подтверждение.');
            $this->loadMatch();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    /**
     * Resubmit a single trip for review after reprocessing
     */
    public function resubmitForReview($tripId)
    {
        try {
            $trip = Trip::with(['trip_hotels', 'trip_migrations', 'trip_documents'])->findOrFail($tripId);

            // Validation: Check if trip has at least one hotel OR migration
            $errors = [];

            if ($trip->trip_hotels->count() === 0 && $trip->trip_migrations->count() === 0) {
                $errors[] = 'Необходимо добавить хотя бы один отель или маршрут';
            }

            if (!empty($errors)) {
                session()->flash('error', implode('. ', $errors));
                return;
            }

            // Determine next operation for this specific trip
            $nextOperationValue = $this->determineNextOperationForTrip($trip);
            $nextOperation = Operation::where('value', $nextOperationValue)->first();

            if (!$nextOperation) {
                throw new \Exception("Операция '{$nextOperationValue}' не найдена в системе.");
            }

            // Update this trip's operation
            // Observer will automatically update match operation
            $trip->update(['operation_id' => $nextOperation->id, 'judge_status' => 0, 'judge_comment' => null]);

            session()->flash('message', 'Командировка успешно отправлена на новую проверку.');
            $this->loadMatch();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function sendToWaitingForProtocol()
    {
        try {
            // Check if all trips are in business_trip_registration
            $allTripsRegistered = $this->match->trips->every(function($trip) {
                return $trip->operation->value === 'business_trip_registration';
            });

            if (!$allTripsRegistered) {
                session()->flash('error', 'Не все командировки находятся в статусе "Регистрация"');
                return;
            }

            // Get waiting_for_protocol operation
            $waitingForProtocolOperation = Operation::where('value', 'waiting_for_protocol')->firstOrFail();

            // Update match operation
            $this->match->update([
                'current_operation_id' => $waitingForProtocolOperation->id
            ]);

            session()->flash('message', 'Матч успешно отправлен на этап "Ожидание протокола"');
            $this->loadMatch();
        } catch (\Exception $e) {
            session()->flash('error', 'Ошибка при отправке матча: ' . $e->getMessage());
        }
    }

    public function goBack()
    {
        return redirect()->route('business-trip-cards');
    }

    public function render()
    {
        return view('livewire.logistician.business-trip-detail')->layout(get_user_layout());
    }
}
