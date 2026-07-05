<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\QuizAttempt;
use Illuminate\Http\Request;

/**
 * Force-submit endpoint for anti-cheat (tab switch / browser close).
 * Uses sendBeacon — no CSRF, verified by signed token.
 * This endpoint is idempotent: if the attempt is already submitted, the request is ignored.
 */
class QuizForceSubmitController extends Controller
{
    public function forceSubmit(Request $request, $tenant, QuizAttempt $attempt)
    {
        // Parse JSON from sendBeacon
        $data = json_decode($request->getContent(), true) ?? [];
        $reason = $data['reason'] ?? 'tab_switch';

        // Idempotent: only submit if still in progress
        if ($attempt->isInProgress()) {
            $attempt->submit($reason);
        }

        return response()->json(['submitted' => true]);
    }
}
