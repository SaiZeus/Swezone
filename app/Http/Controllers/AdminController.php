<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\Attendee;
use App\Models\TicketCategory;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Reusable payment status filter
        $paidFilter = function ($query) {
            $query->whereRaw("LOWER(payment_status) IN ('paid', 'approved', 'completed')");
        };

        // 1. Total Revenue (Calculates orders linked directly to events OR via attendees' ticket categories)
        $totalRevenue = Order::where($paidFilter)
            ->where(function ($query) {
                $query->whereHas('event')
                      ->orWhereHas('attendees.ticketCategory.event');
            })
            ->sum('total_amount');

        // 2. Total Tickets Sold
        $totalTicketsSold = Attendee::whereHas('order', $paidFilter)
            ->whereHas('ticketCategory.event')
            ->count();

        // 3. Active Events Count
        $activeEventsCount = Event::whereIn('status', ['upcoming', 'live'])->count();

        // 4. Loyalty Leaderboard
        $loyaltyRunners = Attendee::select(
                'email',
                DB::raw('MAX(full_name) as full_name'),
                DB::raw('MAX(phone) as phone'),
                DB::raw('count(*) as ticket_count')
            )
            ->whereHas('order', $paidFilter)
            ->groupBy('email')
            ->orderBy('ticket_count', 'desc')
            ->take(10)
            ->get();

        // 5. Recent Orders
        $recentOrders = Order::with(['attendees', 'event'])
            ->latest()
            ->take(5)
            ->get();

        // 6. Revenue Breakdown per Event
        $eventRevenueBreakdown = Event::all()->map(function ($event) use ($paidFilter) {
            $event->real_revenue = Order::where($paidFilter)
                ->where(function ($q) use ($event) {
                    $q->where('event_id', $event->id)
                      ->orWhereHas('attendees.ticketCategory', function ($sub) use ($event) {
                          $sub->where('event_id', $event->id);
                      });
                })
                ->sum('total_amount');
            return $event;
        });

        // 7. Ticket Category Breakdown
        $categoryTicketBreakdown = TicketCategory::with('event')
            ->get()
            ->map(function ($cat) use ($paidFilter) {
                $cat->paid_tickets_count = Attendee::where('ticket_category_id', $cat->id)
                    ->whereHas('order', $paidFilter)
                    ->count();
                return $cat;
            });

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalTicketsSold',
            'activeEventsCount',
            'loyaltyRunners',
            'recentOrders',
            'eventRevenueBreakdown',
            'categoryTicketBreakdown'
        ));
    }
}