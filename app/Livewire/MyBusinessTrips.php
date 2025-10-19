<?php

namespace App\Livewire;

use App\Models\MatchEntity;
use App\Models\Trip;
use App\Models\Operation;
use App\Models\City;
use App\Models\TransportType;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Мои командировки')]
class MyBusinessTrips extends Component
{
    public $activeTab = 'transport-selection';
    public $selectedTrip = null;
    public $showTripDetailModal = false;

    // Transport Selection Form
    public $selectedMatch = null;
    public $transportForm = [
        'departure_city_id' => '',
        'transport_type_id' => '',
    ];

    // Confirmation
    public $confirmationTrip = null;
    public $showConfirmationModal = false;
    public $judge_comment = '';

    // Data
    public $cities = [];
    public $transportTypes = [];

    public function mount()
    {
        $this->authorize('view-own-trips');
        $this->loadCities();
        $this->loadTransportTypes();
    }

    public function loadCities()
    {
        $this->cities = City::orderBy('title_ru')->get();
    }

    public function loadTransportTypes()
    {
        $this->transportTypes = TransportType::orderBy('title_ru')->get();
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function createTrip($matchId)
    {
        $this->validate([
            'transportForm.departure_city_id' => 'required|exists:cities,id',
            'transportForm.transport_type_id' => 'required|exists:transport_types,id',
        ], [
            'transportForm.departure_city_id.required' => 'Выберите город отправления',
            'transportForm.transport_type_id.required' => 'Выберите тип транспорта',
        ]);

        // Check if trip already exists for this judge and match
        $existingTrip = Trip::where('match_id', $matchId)
            ->where('judge_id', auth()->id())
            ->first();

        if ($existingTrip) {
            session()->flash('error', 'Вы уже создали командировку для этого матча');
            return;
        }

        $match = MatchEntity::with(['ownerClub', 'guestClub', 'match_judges'])->findOrFail($matchId);

        // Get business_trip_plan_preparation operation
        $operation = Operation::where('value', 'business_trip_plan_preparation')->firstOrFail();

        // Create trip - Observer will automatically update match operation
        Trip::create([
            'match_id' => $matchId,
            'judge_id' => auth()->id(),
            'operation_id' => $operation->id,
            'departure_city_id' => $this->transportForm['departure_city_id'],
            'transport_type_id' => $this->transportForm['transport_type_id'],
            'name' => ($match->ownerClub->short_name_ru ?? $match->ownerClub->title_ru) . ' - ' . ($match->guestClub->short_name_ru ?? $match->guestClub->title_ru),
            'departure_date' => $match->start_at,
            'judge_status' => 0, // waiting
            'first_status' => 0,
            'final_status' => 0,
        ]);

        session()->flash('message', 'Командировка успешно создана');
        $this->reset('transportForm', 'selectedMatch');
    }

    public function openConfirmationModal($tripId)
    {
        $this->confirmationTrip = Trip::with([
            'match.ownerClub',
            'match.guestClub',
            'match.stadium',
            'match.league',
            'match.season',
            'operation',
            'trip_hotels.hotel_room.hotel',
            'trip_migrations.departure_city',
            'trip_migrations.arrival_city',
            'trip_migrations.transport_type',
            'trip_documents'
        ])->findOrFail($tripId);

        $this->showConfirmationModal = true;
        $this->judge_comment = $this->confirmationTrip->judge_comment ?? '';
    }

    public function closeConfirmationModal()
    {
        $this->showConfirmationModal = false;
        $this->confirmationTrip = null;
        $this->judge_comment = '';
    }

    public function acceptTrip()
    {
        if (!$this->confirmationTrip) return;

        $primaryConfirmationOperation = Operation::where('value', 'primary_business_trip_confirmation')->firstOrFail();

        $this->confirmationTrip->update([
            'judge_status' => 1, // accepted
            'judge_comment' => $this->judge_comment,
            'operation_id' => $primaryConfirmationOperation->id,
            'first_status' => 0,
            'final_status' => 0,
        ]);

        session()->flash('message', 'Командировка принята');
        $this->closeConfirmationModal();
    }

    public function rejectTrip()
    {
        if (!$this->confirmationTrip) return;

        $this->validate([
            'judge_comment' => 'required|string|min:10',
        ], [
            'judge_comment.required' => 'Укажите причину отказа',
            'judge_comment.min' => 'Причина должна содержать минимум 10 символов',
        ]);

        $reprocessingOperation = Operation::where('value', 'business_trip_plan_reprocessing')->firstOrFail();

        $this->confirmationTrip->update([
            'judge_status' => -1, // rejected
            'judge_comment' => $this->judge_comment,
            'operation_id' => $reprocessingOperation->id,
        ]);

        session()->flash('message', 'Командировка отклонена');
        $this->closeConfirmationModal();
    }

    public function openTripDetail($tripId)
    {
        $this->selectedTrip = Trip::with([
            'match.ownerClub',
            'match.guestClub',
            'match.stadium',
            'match.league',
            'match.season',
            'operation',
            'trip_hotels.hotel_room.hotel',
            'trip_migrations.departure_city',
            'trip_migrations.arrival_city',
            'trip_migrations.transport_type',
            'trip_documents'
        ])->findOrFail($tripId);

        $this->showTripDetailModal = true;
    }

    public function closeTripDetail()
    {
        $this->showTripDetailModal = false;
        $this->selectedTrip = null;
    }

    public function render()
    {
        // Tab 1: Transport Selection
        $transportMatches = MatchEntity::with(['ownerClub', 'guestClub', 'stadium', 'league', 'season', 'operation'])
            ->whereHas('operation', function($query) {
                $query->where('value', 'select_transport_departure');
            })
            ->whereHas('match_judges', function($query) {
                $query->where('judge_id', auth()->id());
            })
            ->whereDoesntHave('trips', function($query) {
                $query->where('judge_id', auth()->id());
            })
            ->orderBy('start_at', 'asc')
            ->get();


        // Tab 2: Awaiting Confirmation
        $awaitingConfirmation = Trip::with([
                'match.ownerClub',
                'match.guestClub',
                'match.stadium',
                'match.league',
                'match.season',
                'operation'
            ])
            ->where('judge_id', auth()->id())
            ->whereHas('operation', function($query) {
                $query->where('value', 'referee_team_confirmation');
            })
            ->where('judge_status', 0) // waiting
            ->orderBy('created_at', 'desc')
            ->get();

        // Tab 3: Completed Trips
        $completedTrips = Trip::with([
                'match.ownerClub',
                'match.guestClub',
                'match.stadium',
                'match.league',
                'match.season',
                'operation'
            ])
            ->where('judge_id', auth()->id())
            ->whereHas('operation', function($query) {
                $query->where('value', 'business_trip_registration');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.referee.my-business-trips', [
            'transportMatches' => $transportMatches,
            'awaitingConfirmation' => $awaitingConfirmation,
            'completedTrips' => $completedTrips,
        ])->layout(get_user_layout());
    }
}
