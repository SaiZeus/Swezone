<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MmqrCallbackController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/payment/mmqr/callback', [MmqrCallbackController::class, 'handleCallback']);

Route::get('/check-order-status/{order}', function (\App\Models\Order $order) {
    return response()->json(['status' => $order->payment_status]);
});