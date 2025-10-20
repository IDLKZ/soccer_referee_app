<?php

namespace App\Livewire;

use App\Models\Trip;
use App\Models\Operation;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Финальная проверка командировок')]
class FinalBusinessTripConfirmation extends Component
{
    public $selectedTrip = null;
    public $showDetailModal = false;
    public $final_comment = '';

    // Filters
    public $search = '';
    public $filterLeague = '';
    public $filterDateFrom = '';
    public $filterDateTo = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    public function mount()
    {
        $this->authorize('approve-referee-team');
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterLeague = '';
        $this->filterDateFrom = '';
        $this->filterDateTo = '';
        $this->sortBy = 'created_at';
        $this->sortDirection = 'desc';
    }

    public function sortByField($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function openDetailModal($tripId)
    {
        $this->selectedTrip = Trip::with([
            'match.ownerClub',
            'match.guestClub',
            'match.stadium.city',
            'match.league',
            'match.season',
            'operation',
            'user.role',
            'trip_hotels.hotel_room.hotel',
            'trip_migrations.departure_city',
            'trip_migrations.arrival_city',
            'trip_migrations.transport_type',
            'trip_documents',
            'judge'
        ])->findOrFail($tripId);

        $this->showDetailModal = true;
        $this->final_comment = $this->selectedTrip->final_comment ?? '';
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedTrip = null;
        $this->final_comment = '';
    }

    public function acceptTrip()
    {
        if (!$this->selectedTrip) return;

        $registrationOperation = Operation::where('value', 'business_trip_registration')->firstOrFail();

        $this->selectedTrip->update([
            'final_status' => 1, // accepted
            'final_comment' => $this->final_comment,
            'operation_id' => $registrationOperation->id,
        ]);

        session()->flash('message', 'Командировка принята и отправлена на регистрацию');
        $this->closeDetailModal();
    }

    public function rejectTrip()
    {
        if (!$this->selectedTrip) return;

        $this->validate([
            'final_comment' => 'required|string|min:10',
        ], [
            'final_comment.required' => 'Укажите причину отказа',
            'final_comment.min' => 'Причина должна содержать минимум 10 символов',
        ]);

        $reprocessingOperation = Operation::where('value', 'business_trip_plan_reprocessing')->firstOrFail();

        $this->selectedTrip->update([
            'final_status' => -1, // rejected
            'final_comment' => $this->final_comment,
            'operation_id' => $reprocessingOperation->id,
        ]);

        session()->flash('message', 'Командировка отклонена и отправлена на доработку');
        $this->closeDetailModal();
    }

    public function render()
    {
        $query = Trip::with([
                'match.ownerClub',
                'match.guestClub',
                'match.stadium.city',
                'match.league',
                'match.season',
                'operation',
                'user.role',
                'judge',
                'arrival_city',
                'city'
            ])
            ->whereHas('operation', function($q) {
                $q->where('value', 'final_business_trip_confirmation');
            })
            ->where('final_status', 0); // waiting for final confirmation

        // Search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->whereHas('user', function($userQuery) {
                    $userQuery->where('surname_ru', 'like', '%' . $this->search . '%')
                        ->orWhere('name_ru', 'like', '%' . $this->search . '%')
                        ->orWhere('patronymic_ru', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('match.ownerClub', function($clubQuery) {
                    $clubQuery->where('title_ru', 'like', '%' . $this->search . '%')
                        ->orWhere('short_name_ru', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('match.guestClub', function($clubQuery) {
                    $clubQuery->where('title_ru', 'like', '%' . $this->search . '%')
                        ->orWhere('short_name_ru', 'like', '%' . $this->search . '%');
                });
            });
        }

        // League filter
        if ($this->filterLeague) {
            $query->whereHas('match', function($q) {
                $q->where('league_id', $this->filterLeague);
            });
        }

        // Date range filter
        if ($this->filterDateFrom) {
            $query->whereHas('match', function($q) {
                $q->where('start_at', '>=', $this->filterDateFrom);
            });
        }

        if ($this->filterDateTo) {
            $query->whereHas('match', function($q) {
                $q->where('start_at', '<=', $this->filterDateTo);
            });
        }

        // Sorting
        switch ($this->sortBy) {
            case 'match_date':
                $query->join('matches', 'trips.match_id', '=', 'matches.id')
                    ->orderBy('matches.start_at', $this->sortDirection)
                    ->select('trips.*');
                break;
            case 'referee':
                $query->join('users', 'trips.judge_id', '=', 'users.id')
                    ->orderBy('users.surname_ru', $this->sortDirection)
                    ->select('trips.*');
                break;
            default:
                $query->orderBy($this->sortBy, $this->sortDirection);
                break;
        }

        $trips = $query->get();

        // Get all leagues for filter dropdown
        $leagues = \App\Models\League::orderBy('title_ru')->get();

        // Statistics
        $totalTrips = Trip::whereHas('operation', function($q) {
                $q->where('value', 'final_business_trip_confirmation');
            })
            ->where('final_status', 0)
            ->count();

        return view('livewire.final-business-trip-confirmation', [
            'trips' => $trips,
            'leagues' => $leagues,
            'totalTrips' => $totalTrips,
        ])->layout(get_user_layout());
    }
}
