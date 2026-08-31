<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminEventController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Models\Attendee;
use App\Models\PromoCode;
use App\Mail\TicketConfirmationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

// Public Event Routes
Route::get('/', [EventController::class, 'index'])->name('home');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');

// Waiver & Race Guide Intermediate Page Routes
Route::match(['get', 'post'], '/events/{id}/waiver', [EventController::class, 'showWaiver'])->name('events.waiver');
Route::post('/events/{id}/waiver-accept', [EventController::class, 'acceptWaiver'])->name('events.waiver.accept');
Route::post('/events/{id}/race-guide-accept', [EventController::class, 'acceptRaceGuide'])->name('events.race_guide.accept');

// Promo Code Verification API Route
Route::get('/api/check-promo', function (Request $request) {
    $code = strtoupper(trim($request->query('code')));
    $eventId = $request->query('event_id');
    $categoryId = $request->query('ticket_category_id');

    if (!$code || !$eventId) {
        return response()->json([
            'valid' => false,
            'message' => 'Invalid request details.'
        ]);
    }

    $promo = PromoCode::where('event_id', $eventId)
        ->where(DB::raw('BINARY code'), $code)
        ->first();

    if (!$promo) {
        return response()->json([
            'valid' => false,
            'message' => 'Invalid promo code for this event.'
        ]);
    }

    if (isset($promo->status) && $promo->status !== 'active') {
        return response()->json([
            'valid' => false,
            'message' => 'This promo code is no longer active.'
        ]);
    }

    if (isset($promo->max_uses) && $promo->max_uses > 0 && $promo->uses_count >= $promo->max_uses) {
        return response()->json([
            'valid' => false,
            'message' => 'This promo code has already been used.'
        ]);
    }

    if ($promo->ticket_category_id && $categoryId && $promo->ticket_category_id != $categoryId) {
        return response()->json([
            'valid' => false,
            'message' => 'This code is not applicable to the selected ticket category.'
        ]);
    }

    return response()->json([
        'valid'              => true,
        'promo_code_id'      => $promo->id,
        'discount_type'      => $promo->discount_type,
        'discount_value'     => (float) $promo->discount_value,
        'ticket_category_id' => $promo->ticket_category_id ? (int) $promo->ticket_category_id : null,
    ]);
});

// Checkout & Payment Routes
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/payment/{order}', [CheckoutController::class, 'showPayment'])->name('checkout.payment');
Route::post('/checkout/complete/{order}', [CheckoutController::class, 'completePayment'])->name('checkout.complete');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

// Admin Guest Routes (Authentication)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
});

// Protected Admin Routes (Requires Login)
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Event Routes
    Route::get('/events', [AdminEventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [AdminEventController::class, 'create'])->name('events.create');
    Route::post('/events', [AdminEventController::class, 'store'])->name('events.store');
    Route::get('/events/{id}/edit', [AdminEventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{id}', [AdminEventController::class, 'update'])->name('events.update');
    Route::delete('/events/{id}', [AdminEventController::class, 'destroy'])->name('events.destroy');
    
    // Dedicated Event Promo Code Management Routes
    Route::get('/events/{id}/promo-codes', [AdminEventController::class, 'promoCodes'])->name('events.promo_codes');
    Route::post('/events/{id}/promo-codes', [AdminEventController::class, 'storePromoCode'])->name('events.promo_codes.store');
    Route::delete('/promo-codes/{id}', [AdminEventController::class, 'destroyPromoCode'])->name('promo_codes.destroy');

    // Attendee Routes
    Route::get('/events/{id}/attendees', [AdminEventController::class, 'attendees'])->name('events.attendees');
    Route::put('/attendees/{id}', [AdminEventController::class, 'updateAttendee'])->name('attendees.update');
    Route::delete('/attendees/{id}', [AdminEventController::class, 'destroyAttendee'])->name('attendees.destroy');
});

// Test Email Route
Route::get('/test-email', function () {
    $attendee = Attendee::first();
    if (!$attendee) return "No attendee found in DB.";

    Mail::to('zeuspower200@gmail.com')->send(new TicketConfirmationMail($attendee));
    return "Test email sent!";
});