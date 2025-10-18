<?php

namespace App\Observers;

use App\Models\Trip;
use App\Models\Operation;
use App\Models\MatchEntity;

class TripObserver
{
    /**
     * Handle the Trip "created" event.
     */
    public function created(Trip $trip): void
    {
        $this->updateMatchOperation($trip);
    }

    /**
     * Handle the Trip "updated" event.
     */
    public function updated(Trip $trip): void
    {
        // Only update if status fields changed
        if ($trip->wasChanged(['judge_status', 'first_status', 'final_status', 'operation_id'])) {
            $this->updateMatchOperation($trip);
        }
    }

    /**
     * Handle the Trip "deleted" event.
     */
    public function deleted(Trip $trip): void
    {
        $this->updateMatchOperation($trip);
    }

    /**
     * Handle the Trip "restored" event.
     */
    public function restored(Trip $trip): void
    {
        $this->updateMatchOperation($trip);
    }

    /**
     * Handle the Trip "force deleted" event.
     */
    public function forceDeleted(Trip $trip): void
    {
        //
    }

    /**
     * Update match operation based on all trips' statuses
     */
    private function updateMatchOperation(Trip $trip): void
    {
        // Get all active trips for this match
        $allTrips = Trip::where('match_id', $trip->match_id)
            ->get();

        if ($allTrips->isEmpty()) {
            return;
        }

        // Check if all trips have the same status pattern
        $firstTrip = $allTrips->first();
        $allSamePattern = $allTrips->every(function ($t) use ($firstTrip) {
            return $t->judge_status === $firstTrip->judge_status
                && $t->first_status === $firstTrip->first_status
                && $t->final_status === $firstTrip->final_status;
        });

        // Only update match operation if all trips have the same status pattern
        if (!$allSamePattern) {
            return;
        }

        // Determine the operation based on the common status pattern
        $operationValue = $this->determineOperationValue($firstTrip);

        if ($operationValue) {
            $operation = Operation::where('value', $operationValue)->first();

            if ($operation) {
                // Update match operation without triggering events
                MatchEntity::where('id', $trip->match_id)
                    ->update(['current_operation_id' => $operation->id]);
            }
        }
    }

    /**
     * Determine the operation value based on trip statuses
     */
    private function determineOperationValue(Trip $trip): ?string
    {
        if ($trip->judge_status == 0 && $trip->first_status == 0 && $trip->final_status == 0) {
            return 'referee_team_confirmation';
        } elseif ($trip->judge_status == 1 && $trip->first_status == 0) {
            return 'primary_business_trip_confirmation';
        } elseif ($trip->judge_status == 1 && $trip->first_status == 1) {
            return 'final_business_trip_confirmation';
        }

        return null;
    }
}
