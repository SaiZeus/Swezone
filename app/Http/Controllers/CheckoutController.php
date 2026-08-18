<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Attendee;
use App\Models\PromoCode;
use App\Models\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Mail\TicketConfirmationMail;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    // Process form submission, create pending order and attendee records
    public function process(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'attendees' => 'required|array|min:1',
            'attendees.*.ticket_category_id' => 'required|exists:ticket_categories,id',
            'attendees.*.full_name' => 'required|string|max:255',
            'attendees.*.email' => 'required|email|max:255',
            'attendees.*.phone' => 'required|string|max:50',
            'attendees.*.nrc_passport' => 'required|string|max:100',
            'attendees.*.nationality' => 'required|string|max:100',
            'attendees.*.tshirt_size' => 'required|string|max:10',
        ]);

        $totalAmount = 0;

        // Calculate price based on local vs foreigner status
        foreach ($request->attendees as $attendeeData) {
            $category = TicketCategory::findOrFail($attendeeData['ticket_category_id']);
            if (strtolower($attendeeData['nationality']) === 'foreigner' && $category->foreign_price) {
                $totalAmount += $category->foreign_price;
            } else {
                $totalAmount += $category->local_price;
            }
        }

        // Apply promo code discount if provided
        if ($request->filled('promo_code')) {
            $promo = PromoCode::where('event_id', $request->event_id)
                ->where('code', $request->promo_code)
                ->first();

            if ($promo) {
                if ($promo->discount_type === 'percentage') {
                    $totalAmount -= ($totalAmount * ($promo->discount_value / 100));
                } else {
                    $totalAmount -= $promo->discount_value;
                }
                $totalAmount = max(0, $totalAmount); // Prevent negative totals
            }
        }

        // Create the Order record
        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'total_amount' => $totalAmount,
            'payment_method' => 'MMQR',
            'payment_status' => 'pending',
        ]);

        // Create Attendee records attached to this Order
        foreach ($request->attendees as $attendeeData) {
            Attendee::create([
                'order_id' => $order->id,
                'ticket_category_id' => $attendeeData['ticket_category_id'],
                'full_name' => $attendeeData['full_name'],
                'email' => $attendeeData['email'],
                'phone' => $attendeeData['phone'],
                'nrc_passport' => $attendeeData['nrc_passport'],
                'nationality' => $attendeeData['nationality'],
                'tshirt_size' => $attendeeData['tshirt_size'],
                'ticket_uuid' => (string) Str::uuid(),
            ]);
        }

        return redirect()->route('checkout.payment', $order->id);
    }

    // Display the MMQR payment page with the "Done" button
    public function showPayment($orderId)
    {
        $order = Order::with('attendees.ticketCategory')->findOrFail($orderId);
        return view('checkout.payment', compact('order'));
    }

    // Complete payment (Dummy approval for now until API is connected)
    public function completePayment(Request $request, $orderId)
{
    $order = Order::with('attendees.ticketCategory.event')->findOrFail($orderId);
    
    // Update payment status to paid
    $order->update(['payment_status' => 'paid']);

    // Increment ticket count and dispatch individual emails
    foreach ($order->attendees as $attendee) {
        $category = $attendee->ticketCategory;
        $category->increment('tickets_sold');

        // Send email to the specific runner's email address
        Mail::to($attendee->email)->send(new TicketConfirmationMail($attendee));
    }

    return redirect()->route('checkout.success', $order->id);
}

    // Success landing page
    public function success($orderId)
    {
        $order = Order::with('attendees')->findOrFail($orderId);
        return view('checkout.success', compact('order'));
    }
}