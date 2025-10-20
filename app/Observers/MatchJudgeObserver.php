<?php

namespace App\Observers;

use App\Models\MatchJudge;
use App\Models\MatchEntity;
use App\Models\Operation;

class MatchJudgeObserver
{
    /**
     * Handle the MatchJudge "created" event.
     */
    public function created(MatchJudge $matchJudge): void
    {
        $this->updateMatchOperation($matchJudge);
    }

    /**
     * Handle the MatchJudge "updated" event.
     */
    public function updated(MatchJudge $matchJudge): void
    {
        // Only update if judge_response or final_status changed
        if ($matchJudge->wasChanged(['judge_response', 'final_status'])) {
            $this->updateMatchOperation($matchJudge);
        }
    }

    /**
     * Handle the MatchJudge "deleted" event.
     */
    public function deleted(MatchJudge $matchJudge): void
    {
        $this->updateMatchOperation($matchJudge);
    }

    /**
     * Handle the MatchJudge "restored" event.
     */
    public function restored(MatchJudge $matchJudge): void
    {
        $this->updateMatchOperation($matchJudge);
    }

    /**
     * Handle the MatchJudge "force deleted" event.
     */
    public function forceDeleted(MatchJudge $matchJudge): void
    {
        //
    }

    /**
     * Update match operation based on all match judges' responses
     */
    private function updateMatchOperation(MatchJudge $matchJudge): void
    {
        // Get the match with its requirements and judges
        $match = MatchEntity::with(['judge_requirements', 'match_judges', 'match_logists'])
            ->find($matchJudge->match_id);

        if (!$match) {
            return;
        }

        // Get all match judges for this match
        $allMatchJudges = $match->match_judges;

        if ($allMatchJudges->isEmpty()) {
            return;
        }

        // Calculate total required judges from judge_requirements
        $totalRequiredJudges = $match->judge_requirements->sum('qty');

        // Check if number of match_judges equals total required judges
        if ($allMatchJudges->count() !== $totalRequiredJudges) {
            return;
        }

        // Check if all match_judges have judge_response == 1
        $allAccepted = $allMatchJudges->every(function ($judge) {
            return $judge->judge_response == 1;
        });

        if (!$allAccepted) {
            return;
        }

        // Rule 1: If current_operation_id == 1, move to referee_team_approval (operation id 2)
        if ($match->current_operation_id == 1 || $match->current_operation_id == 3) {
            $operation = Operation::where('value', 'referee_team_approval')->first();

            if ($operation) {
                MatchEntity::where('id', $matchJudge->match_id)
                    ->update(['current_operation_id' => $operation->id]);
            }
            return;
        }

        // Rule 2: If current_operation_id == 2 and all judges have final_status == 1
        if ($match->current_operation_id == 2 || $match->current_operation_id == 3) {
            $allFinalApproved = $allMatchJudges->every(function ($judge) {
                return $judge->final_status == 1;
            });

            if (!$allFinalApproved) {
                return;
            }

            // Check if match has logists
            $hasLogists = $match->match_logists->isNotEmpty();

            if ($hasLogists) {
                // Move to select_transport_departure (operation id 5)
                $operation = Operation::where('value', 'select_transport_departure')->first();
            } else {
                // Move to select_responsible_logisticians (operation id 4)
                $operation = Operation::where('value', 'select_responsible_logisticians')->first();
            }

            if ($operation) {
                MatchEntity::where('id', $matchJudge->match_id)
                    ->update(['current_operation_id' => $operation->id]);
            }
        }
    }
}
