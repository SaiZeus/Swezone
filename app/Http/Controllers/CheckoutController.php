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
    public function process(Request $request)
    {
        $request->validate([
            'event_id'                          => 'required|exists:events,id',
            'attendees'                         => 'required|array|min:1',
            'attendees.*.ticket_category_id'    => 'required|exists:ticket_categories,id',
            'attendees.*.full_name'             => 'required|string|max:255',
            'attendees.*.email'                 => 'required|email|max:255',
            'attendees.*.phone'                 => 'required|string|max:50',
            'attendees.*.viber'                 => 'nullable|string|max:50',
            'attendees.*.nrc_passport'          => 'required|string|max:100',
            'attendees.*.nationality'           => 'required|string|max:100',
            'attendees.*.tshirt_size'           => 'nullable|string|max:10',
            'attendees.*.father_name'           => 'nullable|string|max:255',
            'attendees.*.emergency_contact'     => 'nullable|string|max:50',
            'attendees.*.country'               => 'nullable|string|max:100',
            'attendees.*.gender'                => 'nullable|string',
            'attendees.*.date_of_birth'         => 'nullable|sometimes|date',
            'attendees.*.bib_name'              => 'nullable|string|max:10',
            'attendees.*.blood_type'            => 'nullable|string',
            'attendees.*.has_medical_condition' => 'nullable|string',
            'attendees.*.medical_details'       => 'nullable|string',
            'attendees.*.itra'                  => 'nullable|string',
            'attendees.*.itra_details'          => 'nullable|string',
            'attendees.*.address'               => 'nullable|string',
            'attendees.*.experience'            => 'nullable|string',
        ]);

        $totalAmount = 0;

        $promo = null;
        if ($request->filled('promo_code')) {
            $promo = PromoCode::where('event_id', $request->event_id)
                ->where('code', $request->promo_code)
                ->first();
        }

        foreach ($request->attendees as $attendeeData) {
            $category = TicketCategory::findOrFail($attendeeData['ticket_category_id']);
            
            $itemPrice = (strtolower($attendeeData['nationality']) === 'foreigner' && $category->foreign_price)
                ? $category->foreign_price
                : $category->local_price;

            if ($promo && (is_null($promo->ticket_category_id) || $promo->ticket_category_id == $category->id)) {
                if ($promo->discount_type === 'percentage') {
                    $itemPrice -= ($itemPrice * ($promo->discount_value / 100));
                } else {
                    $itemPrice -= $promo->discount_value;
                }
            }

            $totalAmount += max(0, $itemPrice);
        }

        $order = Order::create([
            'order_number'   => 'ORD-' . strtoupper(Str::random(8)),
            'total_amount'   => $totalAmount,
            'payment_method' => 'MMQR',
            'payment_status' => 'pending',
        ]);

        // Counter array to keep track of sequential numbers within a single order submission
        $categoryOrderCounts = [];
        $eventOrderCount = 0;

        foreach ($request->attendees as $attendeeData) {
            $category = TicketCategory::with('event')->findOrFail($attendeeData['ticket_category_id']);
            $event = $category->event;

            $generatedBib = $attendeeData['bib_name'] ?? null;

            // Generate automatic sequential BIB if enabled for the event
            if ($event && $event->enable_bib_number) {
                if ($event->share_bib_prefix) {
                    $prefix = !empty($event->event_bib_prefix) ? $event->event_bib_prefix : 'BIB';
                    
                    // Total existing attendees across all categories for this event + current loop offset
                    $existingCount = Attendee::whereHas('ticketCategory', function ($q) use ($event) {
                        $q->where('event_id', $event->id);
                    })->count();

                    $startNum = $event->event_bib_start_number ?? 1;
                    $nextNum = $existingCount + $startNum + $eventOrderCount;
                    $eventOrderCount++;
                } else {
                    $prefix = !empty($category->bib_prefix) ? $category->bib_prefix : 'BIB';
                    
                    // Total existing attendees in this specific ticket category + current loop offset
                    $existingCount = Attendee::where('ticket_category_id', $category->id)->count();
                    
                    if (!isset($categoryOrderCounts[$category->id])) {
                        $categoryOrderCounts[$category->id] = 0;
                    }

                    $startNum = $category->bib_start_number ?? 1;
                    $nextNum = $existingCount + $startNum + $categoryOrderCounts[$category->id];
                    $categoryOrderCounts[$category->id]++;
                }

                $generatedBib = strtoupper($prefix) . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
            }

            Attendee::create([
    'order_id'              => $order->id,
    'ticket_category_id'    => $attendeeData['ticket_category_id'],
    'full_name'             => $attendeeData['full_name'],
    'father_name'           => $attendeeData['father_name'] ?? null,
    'email'                 => $attendeeData['email'],
    'phone'                 => $attendeeData['phone'],
    'viber'                 => $attendeeData['viber'] ?? null,
    'emergency_contact'     => $attendeeData['emergency_contact'] ?? null,
    'nrc_passport'          => $attendeeData['nrc_passport'],
    'nationality'           => $attendeeData['nationality'],
    'country'               => $attendeeData['country'] ?? null,
    'gender'                => $attendeeData['gender'] ?? null,
    'date_of_birth'         => !empty($attendeeData['date_of_birth']) ? $attendeeData['date_of_birth'] : null,
    'bib_name'              => $generatedBib,
    'tshirt_size'           => $attendeeData['tshirt_size'] ?? null,
    'blood_type'            => $attendeeData['blood_type'] ?? null,
    'has_medical_condition' => !empty($attendeeData['has_medical_condition']) ? $attendeeData['has_medical_condition'] : 'no', // Fallback to 'no'
    'medical_details'       => $attendeeData['medical_details'] ?? null,
    'itra'                  => !empty($attendeeData['itra']) ? $attendeeData['itra'] : 'no', // Fallback to 'no'
    'itra_details'          => $attendeeData['itra_details'] ?? null,
    'address'               => $attendeeData['address'] ?? null,
    'experience'            => $attendeeData['experience'] ?? null,
    'ticket_uuid'           => (string) Str::uuid(),
]);
        }

        return redirect()->route('checkout.payment', $order->id);
    }

    public function showPayment($orderId)
    {
        $order = Order::with('attendees.ticketCategory')->findOrFail($orderId);
        return view('checkout.payment', compact('order'));
    }

    public function completePayment(Request $request, $orderId)
    {
        $order = Order::with('attendees.ticketCategory.event')->findOrFail($orderId);
        
        $order->update(['payment_status' => 'paid']);

        foreach ($order->attendees as $attendee) {
            $category = $attendee->ticketCategory;
            $category->increment('tickets_sold');

            Mail::to($attendee->email)->send(new TicketConfirmationMail($attendee));
        }

        return redirect()->route('checkout.success', $order->id);
    }

    public function success($orderId)
    {
        $order = Order::with('attendees')->findOrFail($orderId);
        return view('checkout.success', compact('order'));
    }
}