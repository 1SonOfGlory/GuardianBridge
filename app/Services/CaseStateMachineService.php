<?php

namespace App\Services;

use App\Models\ChildCase;
use App\Models\CaseEvent;
use Illuminate\Support\Facades\DB;
use Exception;

class CaseStateMachineService
{
    private const TRANSITIONS = [
        'Received' => ['Investigating'],
        'Investigating' => ['Secured', 'Closed'],
        'Secured' => ['Closed'],
        'Closed' => []
    ];

    /**
     * Transition a case to a new state and log the append-only event.
     */
    public function transition(ChildCase $childCase, string $newState, ?int $userId = null, array $payload = []): void
    {
        $currentState = $childCase->state ?? 'Received';

        if (!in_array($newState, self::TRANSITIONS[$currentState] ?? [])) {
            throw new Exception("Invalid state transition from {$currentState} to {$newState}.");
        }

        DB::transaction(function () use ($childCase, $currentState, $newState, $userId, $payload) {
            $childCase->update(['state' => $newState]);

            CaseEvent::create([
                'child_case_id' => $childCase->id,
                'user_id' => $userId,
                'from_state' => $currentState,
                'to_state' => $newState,
                'event_type' => 'state_transition',
                'payload' => json_encode($payload)
            ]);
        });
    }
}
