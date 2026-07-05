<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Payment webhook handler.
 * Each method verifies the webhook signature, updates the order, and credits tokens.
 * Full implementation in Phase 3.
 */
class PaymentWebhookController extends Controller
{
    public function midtrans(Request $request)
    {
        // Will be implemented in Phase 3
        return response()->json(['status' => 'ok']);
    }

    public function xendit(Request $request)
    {
        return response()->json(['status' => 'ok']);
    }

    public function ipaymu(Request $request)
    {
        return response()->json(['status' => 'ok']);
    }

    public function doku(Request $request)
    {
        return response()->json(['status' => 'ok']);
    }

    public function duitku(Request $request)
    {
        return response()->json(['status' => 'ok']);
    }
}
