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
