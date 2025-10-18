<?php

namespace App\Observers;

use App\Models\Protocol;

class ProtocolObserver
{
    /**
     * Handle the Protocol "created" event.
     */
    public function created(Protocol $protocol): void
    {
        //
    }

    /**
     * Handle the Protocol "updated" event.
     */
    public function updated(Protocol $protocol): void
    {
        // Check if operation_id was changed (protocol was submitted for approval)
        if ($protocol->isDirty('operation_id')) {
            $this->updateMatchOperation($protocol);
        }

        // Check if final_status was changed to 1 (all protocols approved)
        if ($protocol->isDirty('final_status') && $protocol->final_status == 1) {
            $this->checkAllProtocolsApproved($protocol);
        }
    }

    /**
     * Update match operation when all required protocols are submitted
     */
    protected function updateMatchOperation(Protocol $protocol): void
    {
        $match = $protocol->match;
        if (!$match) return;

        // Get all protocols for this match
        $allProtocols = \App\Models\Protocol::where('match_id', $match->id)->get();

        // Check if all protocols have the same operation value
        $protocolOperationValues = $allProtocols->pluck('operation.value')->unique();

        // If all protocols are in primary_protocol_approval
        if ($protocolOperationValues->count() === 1 &&
            $protocolOperationValues->first() === 'primary_protocol_approval') {

            $operation = \App\Models\Operation::where('value', 'primary_protocol_approval')->first();
            if ($operation) {
                $match->update(['current_operation_id' => $operation->id]);
            }
        }

        // If all protocols are in control_protocol_approval
        elseif ($protocolOperationValues->count() === 1 &&
                $protocolOperationValues->first() === 'control_protocol_approval') {

            $operation = \App\Models\Operation::where('value', 'control_protocol_approval')->first();
            if ($operation) {
                $match->update(['current_operation_id' => $operation->id]);
            }
        }
    }

    /**
     * Check if all protocols for the match are approved (final_status == 1)
     * If so, move match to avr_created_waiting_processing operation
     */
    protected function checkAllProtocolsApproved(Protocol $protocol): void
    {
        $match = $protocol->match;
        if (!$match) return;

        // Get all protocols for this match
        $allProtocols = \App\Models\Protocol::where('match_id', $match->id)->get();

        // Check if all protocols have final_status == 1
        $allApproved = $allProtocols->every(function($protocol) {
            return $protocol->final_status == 1;
        });

        if ($allApproved && $allProtocols->count() > 0) {
            // Move match to avr_created_waiting_processing
            $operation = \App\Models\Operation::where('value', 'avr_created_waiting_processing')->first();
            if ($operation) {
                $match->update(['current_operation_id' => $operation->id]);
            }
        }
    }

    /**
     * Handle the Protocol "deleted" event.
     */
    public function deleted(Protocol $protocol): void
    {
        //
    }

    /**
     * Handle the Protocol "restored" event.
     */
    public function restored(Protocol $protocol): void
    {
        //
    }

    /**
     * Handle the Protocol "force deleted" event.
     */
    public function forceDeleted(Protocol $protocol): void
    {
        //
    }
}
