<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Attendee;
use Illuminate\Http\Request;

class EventController extends Controller
{
    // Homepage displaying Upcoming, Live, and Past events
    public function index()
    {
        $upcomingEvents = Event::where('status', 'upcoming')->get();
        $liveEvents     = Event::where('status', 'live')->get();
        $pastEvents     = Event::where('status', 'past')->get();

        return view('index', compact('upcomingEvents', 'liveEvents', 'pastEvents'));
    }

    // Event details page with Ticket options & Live Board
    public function show($id)
    {
        $event = Event::with(['ticketCategories', 'promoCodes'])->findOrFail($id);

        // Fetch paid attendees grouped by ticket category for the Live Board
        $liveBoard = Attendee::whereHas('order', function ($query) {
            $query->where('payment_status', 'paid');
        })
        ->whereIn('ticket_category_id', $event->ticketCategories->pluck('id'))
        ->get()
        ->groupBy('ticket_category_id');

        return view('events.show', compact('event', 'liveBoard'));
    }
}

