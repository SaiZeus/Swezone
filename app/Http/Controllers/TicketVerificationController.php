<?php

namespace App\Http\Controllers;

use App\Models\Attendee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TicketVerificationController extends Controller
{
    public function show($token)
    {
        $attendee = Attendee::with('ticketCategory.event')->where('verification_token', $token)->firstOrFail();
        
        $eventDate = Carbon::parse($attendee->ticketCategory->event->event_date);
        $isExpired = Carbon::now()->greaterThan($eventDate);

        return view('verify.status', [
            'attendee' => $attendee,
            'isExpired' => $isExpired
        ]);
    }
}