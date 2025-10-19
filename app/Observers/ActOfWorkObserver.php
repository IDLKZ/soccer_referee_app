<?php

namespace App\Observers;

use App\Models\ActOfWork;
use App\Models\Operation;

class ActOfWorkObserver
{
    /**
     * Handle the ActOfWork "created" event.
     */
    public function created(ActOfWork $actOfWork): void
    {
        $this->updateMatchOperation($actOfWork);
    }

    /**
     * Handle the ActOfWork "updated" event.
     */
    public function updated(ActOfWork $actOfWork): void
    {
        $this->updateMatchOperation($actOfWork);
    }

    /**
     * Handle the ActOfWork "deleted" event.
     */
    public function deleted(ActOfWork $actOfWork): void
    {
        //
    }

    /**
     * Handle the ActOfWork "restored" event.
     */
    public function restored(ActOfWork $actOfWork): void
    {
        //
    }

    /**
     * Handle the ActOfWork "force deleted" event.
     */
    public function forceDeleted(ActOfWork $actOfWork): void
    {
        //
    }

    /**
     * Update match operation based on all act_of_works statuses
     */
    private function updateMatchOperation(ActOfWork $actOfWork): void
    {
        $match = $actOfWork->match;

        if (!$match) {
            return;
        }

        // Get all act_of_works for this match
        $allAvrs = ActOfWork::where('match_id', $match->id)
            ->whereNull('deleted_at')
            ->get();

        if ($allAvrs->isEmpty()) {
            return;
        }

        // Check if all AVRs meet certain criteria and update match operation accordingly
        // The order matters - check from most complete to least complete

        // Check if all have first_status == 1 && judge_status == 1 && control_status == 1 && first_financial_status == 1 && last_financial_status == 1 && final_status == 1
        if ($allAvrs->every(function($avr) {
            return $avr->first_status == 1 && $avr->judge_status == 1 &&
                   $avr->control_status == 1 && $avr->first_financial_status == 1 &&
                   $avr->last_financial_status == 1 && $avr->final_status == 1;
        })) {
            $operation = Operation::where('value', 'avr_confirmed_waiting_payment')->first();
            if ($operation) {
                $match->update(['current_operation_id' => $operation->id]);
            }
            return;
        }

        // Check if all have first_status == 1 && judge_status == 1 && control_status == 1 && first_financial_status == 1 && last_financial_status == 1
        if ($allAvrs->every(function($avr) {
            return $avr->first_status == 1 && $avr->judge_status == 1 &&
                   $avr->control_status == 1 && $avr->first_financial_status == 1 &&
                   $avr->last_financial_status == 1;
        })) {
            $operation = Operation::where('value', 'control_financial_check')->first();
            if ($operation) {
                $match->update(['current_operation_id' => $operation->id]);
            }
            return;
        }

        // Check if all have first_status == 1 && judge_status == 1 && control_status == 1 && first_financial_status == 1
        if ($allAvrs->every(function($avr) {
            return $avr->first_status == 1 && $avr->judge_status == 1 &&
                   $avr->control_status == 1 && $avr->first_financial_status == 1;
        })) {
            $operation = Operation::where('value', 'primary_financial_check')->first();
            if ($operation) {
                $match->update(['current_operation_id' => $operation->id]);
            }
            return;
        }

        // Check if all have first_status == 1 && judge_status == 1 && control_status == 1
        if ($allAvrs->every(function($avr) {
            return $avr->first_status == 1 && $avr->judge_status == 1 && $avr->control_status == 1;
        })) {
            $operation = Operation::where('value', 'avr_approval_by_committee')->first();
            if ($operation) {
                $match->update(['current_operation_id' => $operation->id]);
            }
            return;
        }

        // Check if all have first_status == 1 && judge_status == 1
        if ($allAvrs->every(function($avr) {
            return $avr->first_status == 1 && $avr->judge_status == 1;
        })) {
            $operation = Operation::where('value', 'avr_approval_by_committee')->first();
            if ($operation) {
                $match->update(['current_operation_id' => $operation->id]);
            }
            return;
        }

        // Check if all have first_status == 1
        if ($allAvrs->every(function($avr) {
            return $avr->first_status == 1;
        })) {
            $operation = Operation::where('value', 'avr_processing')->first();
            if ($operation) {
                $match->update(['current_operation_id' => $operation->id]);
            }
            return;
        }
    }
}
