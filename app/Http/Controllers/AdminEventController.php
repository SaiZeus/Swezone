<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\TicketCategory;
use App\Models\PromoCode;
use App\Models\Attendee;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminEventController extends Controller
{
    public function index()
    {
        $events = Event::with('ticketCategories')->withCount('ticketCategories')->latest()->get();
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string',
            'event_date' => 'required|date',
            'status' => 'required|in:upcoming,live,past',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categories' => 'required|array|min:1',
            'categories.*.name' => 'required|string',
            'categories.*.local_price' => 'required|numeric|min:0',
            'categories.*.foreign_price' => 'nullable|numeric|min:0',
            'categories.*.capacity' => 'nullable|integer|min:1',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('events', 'public');
        }

        $event = Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'event_date' => $request->event_date,
            'status' => $request->status,
            'image' => $imagePath,
        ]);

        foreach ($request->categories as $categoryData) {
            TicketCategory::create([
                'event_id' => $event->id,
                'name' => $categoryData['name'],
                'local_price' => $categoryData['local_price'],
                'foreign_price' => $categoryData['foreign_price'] ?? null,
                'capacity' => $categoryData['capacity'] ?? null,
            ]);
        }

        if ($request->filled('promo_code') && $request->filled('promo_value')) {
            PromoCode::create([
                'event_id' => $event->id,
                'code' => strtoupper($request->promo_code),
                'discount_type' => $request->promo_type ?? 'fixed',
                'discount_value' => $request->promo_value,
            ]);
        }

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully!');
    }

    public function edit($id)
    {
        $event = Event::with(['ticketCategories', 'promoCodes'])->findOrFail($id);
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string',
            'event_date' => 'required|date',
            'status' => 'required|in:upcoming,live,past',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $event->image = $request->file('image')->store('events', 'public');
        }

        $event->update([
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'event_date' => $request->event_date,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully!');
    }

    public function attendees($eventId)
{
    $event = Event::with('ticketCategories')->findOrFail($eventId);

    // Fetch ONLY attendees whose order is marked paid/approved
    $attendees = Attendee::whereHas('ticketCategory', function ($q) use ($eventId) {
        $q->where('event_id', $eventId);
    })
    ->whereHas('order', function ($q) {
        $q->whereRaw("LOWER(payment_status) IN ('paid', 'approved', 'completed')");
    })
    ->with(['ticketCategory', 'order'])
    ->get();

    // Calculate real revenue via event_id OR linked attendee ticket categories
    $totalRevenue = Order::where(function ($q) use ($eventId) {
            $q->where('event_id', $eventId)
              ->orWhereHas('attendees.ticketCategory', function ($sub) use ($eventId) {
                  $sub->where('event_id', $eventId);
              });
        })
        ->whereRaw("LOWER(payment_status) IN ('paid', 'approved', 'completed')")
        ->sum('total_amount');

    return view('admin.events.attendees', compact('event', 'attendees', 'totalRevenue'));
}

    public function updateAttendee(Request $request, $id)
    {
        $attendee = Attendee::findOrFail($id);
        $attendee->update($request->only(['full_name', 'email', 'phone', 'nrc_passport', 'tshirt_size', 'nationality']));
        return back()->with('success', 'Attendee updated successfully!');
    }

    public function destroyAttendee($id)
    {
        $attendee = Attendee::findOrFail($id);
        $attendee->delete();
        return back()->with('success', 'Attendee deleted successfully!');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        // Clean up linked data
        $categoryIds = $event->ticketCategories()->pluck('id');
        Attendee::whereIn('ticket_category_id', $categoryIds)->delete();
        Order::where('event_id', $event->id)->delete();
        $event->ticketCategories()->delete();
        $event->promoCodes()->delete();

        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully!');
    }
}