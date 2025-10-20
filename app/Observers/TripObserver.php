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
        $this->checkAndMoveToBusinessTripPlanPreparation($trip);
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
        // Get the match with judges
        $match = MatchEntity::with('match_judges')->find($trip->match_id);

        if (!$match) {
            return;
        }

        // Get all active trips for this match
        $allTrips = Trip::where('match_id', $trip->match_id)->get();

        if ($allTrips->isEmpty()) {
            return;
        }

        // Count match judges (утвержденные судьи на матч)
        $matchJudgesCount = $match->match_judges()->count();

        // Check if number of trips equals number of match judges
        // Пока все trips не созданы для всех судей - не меняем статус
        if ($allTrips->count() !== $matchJudgesCount) {
            return;
        }

        // Determine the operation based on all trips' statuses
        $operationValue = $this->determineOperationValueForAllTrips($allTrips);

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
     * Determine the operation value based on ALL trips' statuses
     */
    private function determineOperationValueForAllTrips($allTrips): ?string
    {
        // Rule 1: All trips have judge_status == 1, first_status == 1, final_status == 1
        // → business_trip_registration
        if ($allTrips->every(fn($t) => $t->judge_status == 1 && $t->first_status == 1 && $t->final_status == 1)) {
            return 'business_trip_registration';
        }

        // Rule 2: All trips have judge_status == 1, first_status == 1, final_status in (0, -1)
        // → final_business_trip_confirmation
        if ($allTrips->every(fn($t) => $t->judge_status == 1 && $t->first_status == 1 && in_array($t->final_status, [0, -1]))) {
            return 'final_business_trip_confirmation';
        }

        // Rule 3: All trips have judge_status == 1, first_status in (0, -1), final_status in (0, -1)
        // → primary_business_trip_confirmation
        if ($allTrips->every(fn($t) => $t->judge_status == 1 && in_array($t->first_status, [0, -1]) && in_array($t->final_status, [0, -1]))) {
            return 'primary_business_trip_confirmation';
        }

        // Rule 4: All trips have judge_status == 0, first_status == 0, final_status == 0
        // → referee_team_confirmation
        if ($allTrips->every(fn($t) => $t->judge_status == 0 && $t->first_status == 0 && $t->final_status == 0)) {
            return 'referee_team_confirmation';
        }

        return null;
    }

    /**
     * Check if all trips are created and move match to business_trip_plan_preparation
     * Only triggers when a trip is created and match is in select_transport_departure stage
     */
    private function checkAndMoveToBusinessTripPlanPreparation(Trip $trip): void
    {
        // Get the match with its current operation
        $match = MatchEntity::with(['operation', 'match_judges'])->find($trip->match_id);

        if (!$match || !$match->operation) {
            return;
        }

        // Only proceed if match is in select_transport_departure stage
        if ($match->operation->value !== 'select_transport_departure') {
            return;
        }

        // Count total match judges
        $matchJudgesCount = $match->match_judges()->count();

        // Count total trips created for this match
        $tripsCount = Trip::where('match_id', $trip->match_id)->count();

        // If all trips are created (trips count equals match judges count)
        if ($tripsCount === $matchJudgesCount && $matchJudgesCount > 0) {
            // Get business_trip_plan_preparation operation
            $businessTripPlanOperation = Operation::where('value', 'business_trip_plan_preparation')->first();

            if ($businessTripPlanOperation) {
                // Update match operation without triggering events
                MatchEntity::where('id', $trip->match_id)
                    ->update(['current_operation_id' => $businessTripPlanOperation->id]);
            }
        }
    }
}
