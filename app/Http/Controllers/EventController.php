<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with(['ticketCategories', 'items'])
            ->select('*')
            ->selectRaw("
                CASE 
                    WHEN status = 'live' THEN 1 
                    WHEN status = 'upcoming' THEN 2 
                    WHEN status = 'past' THEN 3 
                    ELSE 4 
                END as status_priority
            ")
            ->orderBy('status_priority', 'asc')
            ->orderBy('event_date', 'asc')
            ->get();

        $liveEvents     = $events->where('status', 'live');
        $upcomingEvents = $events->where('status', 'upcoming');
        $pastEvents     = $events->where('status', 'past');

        return view('index', compact('events', 'liveEvents', 'upcomingEvents', 'pastEvents'));
    }

    public function show($id)
    {
        $event = Event::with(['ticketCategories', 'promoCodes', 'items'])->findOrFail($id);

        $totalTicketsSold = $event->ticketCategories->sum('tickets_sold');
        
        // Calculate remaining overall event capacity
        $overallCapacity = $event->overall_capacity;
        $overallTicketsRemaining = $overallCapacity !== null ? max(0, $overallCapacity - $totalTicketsSold) : null;
        $isEventSoldOut = $overallCapacity !== null && ($totalTicketsSold >= $overallCapacity);

        return view('events.show', compact('event', 'totalTicketsSold', 'overallTicketsRemaining', 'isEventSoldOut'));
    }

    /**
     * Show Intermediate Waiver Blade before Race Guide / Payment
     */
    public function showWaiver(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $attendeesInput = $request->input('attendees', session('_old_input.attendees', []));
        $promoCode      = $request->input('promo_code', session('_old_input.promo_code'));

        if (empty($attendeesInput)) {
            return redirect()->route('events.show', $id)
                ->with('error', 'Please complete registration details first.');
        }

        $attendees = [];
        foreach ($attendeesInput as $index => $data) {
            $nrcPassport = $data['nrc_passport'] ?? null;

            if (empty($nrcPassport)) {
                if (isset($data['nationality']) && $data['nationality'] === 'Foreigner') {
                    $nrcPassport = $data['passport_number'] ?? null;
                } else {
                    $state    = $data['nrc_state'] ?? '';
                    $district = $data['nrc_district'] ?? '';
                    $naing    = $data['nrc_naing'] ?? '';
                    $number   = $data['nrc_number'] ?? '';

                    if ($state && $district && $naing && $number) {
                        $nrcPassport = "{$state}/{$district}({$naing}){$number}";
                    }
                }
            }

            $data['nrc_passport'] = $nrcPassport;
            $attendees[$index] = $data;
        }

        return view('events.waiver', compact('event', 'attendees', 'promoCode'));
    }

    /**
     * Handle Waiver Submission -> Route to Race Guide or Checkout Process
     */
    public function acceptWaiver(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $attendees = $request->input('attendees', []);
        $promoCode = $request->input('promo_code');

        if (empty($attendees)) {
            return redirect()->route('events.show', $id)
                ->with('error', 'Registration session expired. Please select your tickets again.');
        }

        // Strict non-empty check for race guide file paths
        $hasRaceGuide = !empty(trim($event->english_race_guide ?? '')) || !empty(trim($event->burmese_race_guide ?? ''));

        if ($hasRaceGuide) {
            return view('events.race_guide', compact('event', 'attendees', 'promoCode'));
        }

        // If no Race Guide exists, forward directly to CheckoutController@process
        return app(CheckoutController::class)->process($request);
    }

    /**
     * Handle Race Guide Submission -> Route to Checkout Process
     */
    public function acceptRaceGuide(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $attendees = $request->input('attendees', []);

        if (empty($attendees)) {
            return redirect()->route('events.show', $id)
                ->with('error', 'Registration session expired. Please select your tickets again.');
        }

        return app(CheckoutController::class)->process($request);
    }
}