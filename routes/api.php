<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MmqrCallbackController;
use App\Models\PromoCode;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/payment/mmqr/callback', [MmqrCallbackController::class, 'handleCallback']);

Route::get('/check-order-status/{order}', function (\App\Models\Order $order) {
    return response()->json(['status' => $order->payment_status]);
});

// ADD THIS ROUTE FOR PROMO CODE VALIDATION
Route::get('/check-promo', function (Request $request) {
    $code = strtoupper(trim($request->query('code', '')));
    $eventId = $request->query('event_id');
    $ticketCategoryId = $request->query('ticket_category_id');

    if (empty($code) || empty($eventId)) {
        return response()->json([
            'valid' => false,
            'message' => 'Invalid parameters provided.',
        ]);
    }

    $promo = PromoCode::where('event_id', $eventId)
        ->where('code', $code)
        ->where('status', 'active')
        ->first();

    if (!$promo) {
        return response()->json([
            'valid' => false,
            'message' => 'Invalid or inactive promo code.',
        ]);
    }

    // Check scope if promo code is restricted to a specific ticket category
    if (!is_null($promo->ticket_category_id) && $promo->ticket_category_id != $ticketCategoryId) {
        return response()->json([
            'valid' => false,
            'message' => 'This promo code is not applicable to this ticket category.',
        ]);
    }

    // Check usage limits
    if (!is_null($promo->max_uses) && $promo->uses_count >= $promo->max_uses) {
        return response()->json([
            'valid' => false,
            'message' => 'This promo code limit has been reached.',
        ]);
    }

    return response()->json([
        'valid'          => true,
        'promo_code_id'  => $promo->id,
        'discount_type'  => $promo->discount_type, // 'percentage' or 'fixed'
        'discount_value' => (float) $promo->discount_value,
    ]);
});