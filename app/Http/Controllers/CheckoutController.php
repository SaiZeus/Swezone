<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Attendee;
use App\Models\User;
use App\Models\PromoCode;
use App\Models\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Mail\TicketConfirmationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        $request->validate([
            'event_id'                         => 'required|exists:events,id',
            'attendees'                        => 'required|array|min:1',
            'attendees.*.ticket_category_id'   => 'required|exists:ticket_categories,id',
            'attendees.*.promo_code_id'        => 'nullable|exists:promo_codes,id',
            'attendees.*.full_name'            => 'required|string|max:255',
            'attendees.*.email'                => 'required|email|max:255',
            'attendees.*.phone'                => 'required|string|max:50',
            'attendees.*.viber'                => 'nullable|string|max:50',
            'attendees.*.nrc_passport'         => 'required|string|max:100',
            'attendees.*.nationality'          => 'required|string|max:100',
            'attendees.*.tshirt_size'          => 'nullable|string|max:10',
            'attendees.*.father_name'          => 'nullable|string|max:255',
            'attendees.*.emergency_contact'    => 'nullable|string|max:50',
            'attendees.*.country'              => 'nullable|string|max:100',
            'attendees.*.gender'               => 'nullable|string',
            'attendees.*.date_of_birth'        => 'nullable|sometimes|date',
            'attendees.*.bib_name'             => 'nullable|string|max:10',
            'attendees.*.blood_type'           => 'nullable|string',
            'attendees.*.has_medical_condition'=> 'nullable|string',
            'attendees.*.medical_details'      => 'nullable|string',
            'attendees.*.itra'                 => 'nullable|string',
            'attendees.*.itra_details'         => 'nullable|string',
            'attendees.*.address'              => 'nullable|string',
            'attendees.*.experience'           => 'nullable|string',
        ]);

        $totalAmount = 0;

        // 1. Calculate final discounted amount for order
        foreach ($request->attendees as $attendeeData) {
            $category = TicketCategory::findOrFail($attendeeData['ticket_category_id']);
            
            $itemPrice = (strtolower($attendeeData['nationality']) === 'foreigner' && $category->foreign_price)
                ? (float) $category->foreign_price
                : (float) $category->local_price;

            if (!empty($attendeeData['promo_code_id'])) {
                $promo = PromoCode::find($attendeeData['promo_code_id']);

                if ($promo) {
                    $isEventMatch = (int)$promo->event_id === (int)$request->event_id;
                    $isCategoryMatch = is_null($promo->ticket_category_id) || ((int)$promo->ticket_category_id === (int)$category->id);

                    if ($isEventMatch && $isCategoryMatch) {
                        if ($promo->discount_type === 'percentage') {
                            $itemDiscount = ($itemPrice * ((float)$promo->discount_value / 100));
                        } else {
                            $itemDiscount = (float) $promo->discount_value;
                        }

                        $itemPrice -= $itemDiscount;
                    }
                }
            }

            $totalAmount += max(0, $itemPrice);
        }

        // Round to 2 decimal places to ensure floating point accuracy for CB Bank payload
        $totalAmount = round($totalAmount, 2);

        $order = Order::create([
            'event_id'       => $request->event_id,
            'order_number'   => 'ORD-' . strtoupper(Str::random(8)),
            'total_amount'   => $totalAmount,
            'payment_method' => 'MMQR',
            'payment_status' => 'pending',
        ]);

        $allocatedRegNumbers = [];
        $allocatedSharedBibs = [];
        $allocatedCategoryBibs = [];

        foreach ($request->attendees as $attendeeData) {
            $category = TicketCategory::with('event')->findOrFail($attendeeData['ticket_category_id']);
            $event = $category->event;

            $generatedBib = $attendeeData['bib_name'] ?? null;

            if ($event && $event->enable_bib_number) {
                if ($event->share_bib_prefix) {
                    $prefix = !empty($event->event_bib_prefix) ? $event->event_bib_prefix : 'BIB';
                    $startNum = $event->event_bib_start_number ?? 1;
                    
                    $dbBibs = Attendee::whereHas('ticketCategory', function ($q) use ($event) {
                            $q->where('event_id', $event->id);
                        })
                        ->withTrashed()
                        ->pluck('bib_name')
                        ->map(function ($bib) use ($prefix) {
                            $clean = str_replace(strtoupper($prefix) . '-', '', strtoupper($bib));
                            return is_numeric($clean) ? (int)$clean : null;
                        })
                        ->filter()
                        ->toArray();

                    $usedBibs = array_merge($dbBibs, $allocatedSharedBibs[$event->id] ?? []);
                    sort($usedBibs);

                    $nextNum = $startNum;
                    foreach ($usedBibs as $num) {
                        if ($num == $nextNum) {
                            $nextNum++;
                        } elseif ($num > $nextNum) {
                            break;
                        }
                    }
                    $allocatedSharedBibs[$event->id][] = $nextNum;
                } else {
                    $prefix = !empty($category->bib_prefix) ? $category->bib_prefix : 'BIB';
                    $startNum = $category->bib_start_number ?? 1;
                    
                    $dbBibs = Attendee::where('ticket_category_id', $category->id)
                        ->withTrashed()
                        ->pluck('bib_name')
                        ->map(function ($bib) use ($prefix) {
                            $clean = str_replace(strtoupper($prefix) . '-', '', strtoupper($bib));
                            return is_numeric($clean) ? (int)$clean : null;
                        })
                        ->filter()
                        ->toArray();

                    $usedBibs = array_merge($dbBibs, $allocatedCategoryBibs[$category->id] ?? []);
                    sort($usedBibs);

                    $nextNum = $startNum;
                    foreach ($usedBibs as $num) {
                        if ($num == $nextNum) {
                            $nextNum++;
                        } elseif ($num > $nextNum) {
                            break;
                        }
                    }
                    $allocatedCategoryBibs[$category->id][] = $nextNum;
                }

                $generatedBib = strtoupper($prefix) . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
            }

            $dbRegs = Attendee::whereHas('ticketCategory', function ($q) use ($event) {
                    $q->where('event_id', $event->id);
                })
                ->withTrashed()
                ->pluck('ticket_code')
                ->map(function ($code) {
                    $clean = str_replace('REG-', '', $code);
                    return is_numeric($clean) ? (int)$clean : null;
                })
                ->filter()
                ->toArray();

            $usedRegs = array_merge($dbRegs, $allocatedRegNumbers[$event->id] ?? []);
            sort($usedRegs);

            $nextRegNum = 1;
            foreach ($usedRegs as $num) {
                if ($num == $nextRegNum) {
                    $nextRegNum++;
                } elseif ($num > $nextRegNum) {
                    break;
                }
            }
            $allocatedRegNumbers[$event->id][] = $nextRegNum;
            $registrationCode = 'REG-' . $nextRegNum;

            $user = User::firstOrCreate(
                ['email' => $attendeeData['email']],
                [
                    'name'     => $attendeeData['full_name'],
                    'password' => bcrypt(Str::random(16)),
                ]
            );

            $userCode = 'SWE-' . str_pad($user->id, 4, '0', STR_PAD_LEFT);

            Attendee::create([
                'order_id'              => $order->id,
                'ticket_category_id'    => $attendeeData['ticket_category_id'],
                'promo_code_id'         => $attendeeData['promo_code_id'] ?? null,
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
                'has_medical_condition' => !empty($attendeeData['has_medical_condition']) ? $attendeeData['has_medical_condition'] : 'no',
                'medical_details'       => $attendeeData['medical_details'] ?? null,
                'itra'                  => !empty($attendeeData['itra']) ? $attendeeData['itra'] : 'no',
                'itra_details'          => $attendeeData['itra_details'] ?? null,
                'address'               => $attendeeData['address'] ?? null,
                'experience'            => $attendeeData['experience'] ?? null,
                'ticket_uuid'           => (string) Str::uuid(),
                'ticket_code'           => $registrationCode,
                'user_code'             => $userCode,
            ]);
        }

        return redirect()->route('checkout.payment', $order);
    }

    protected function generateMmqrCode($orderNumber, $amount)
    {
        $env = config('mmqr.environment', 'uat');
        $config = config("mmqr.{$env}");

        try {
            $tokenResponse = Http::post("{$config['base_url']}/auth/token", [
                'userId'   => $config['user_id'],
                'password' => $config['password'],
            ]);

            if (!$tokenResponse->successful() || empty($tokenResponse['token'])) {
                Log::error('MMQR Auth Token Failed', ['response' => $tokenResponse->json()]);
                return null;
            }

            $token = $tokenResponse['token'];

            $qrResponse = Http::post("{$config['base_url']}/dynamicqr", [
                'token'                => $token,
                'sourceId'             => $config['source_id'],
                'transCurrency'        => '104',
                'transAmount'          => (float) $amount,
                'purposeOfTransaction' => 'Marathon Ticket',
                'referenceNo'          => $orderNumber,
                'merchantId'           => $config['mid'],
                'terminalId'           => $config['tid'],
            ]);

            if ($qrResponse->successful() && ($qrResponse['returnCode'] ?? null) === '0000') {
                return [
                    'qr_code_info'    => $qrResponse['qrCodeInfo'],
                    'qr_reference_no' => $qrResponse['qrReferenceNo'],
                    'expired_at'      => $qrResponse['qrExpiredDateTime'] ?? null,
                ];
            }

            Log::error('MMQR Dynamic QR Generation Failed', ['response' => $qrResponse->json()]);
            return null;
        } catch (Exception $e) {
            Log::error('MMQR Exception Encountered', ['error' => $e->getMessage()]);
            return null;
        }
    }

    public function showPayment(Order $order)
    {
        // Prevent going back to payment if already paid
        if ($order->payment_status === 'paid') {
            return redirect()->route('checkout.success', $order);
        }

        // Check if order is pending and older than 3 minutes
        if ($order->payment_status === 'pending' && $order->created_at->lt(now()->subMinutes(3))) {
            $order->attendees()->delete();
            $order->delete();

            return redirect()->route('home')->with('error', 'Your payment session has expired after 3 minutes. Please register again.');
        }

        $order->load('attendees.ticketCategory');
        $mmqrData = $this->generateMmqrCode($order->order_number, $order->total_amount);

        return view('checkout.payment', compact('order', 'mmqrData'));
    }

    public function completePayment(Request $request, Order $order)
    {
        $order->load('attendees.ticketCategory.event');
        
        $order->update(['payment_status' => 'paid']);

        foreach ($order->attendees as $attendee) {
            $category = $attendee->ticketCategory;
            if ($category) {
                $category->increment('tickets_sold');
            }

            // Increment promo code usage count only after successful payment completion
            if (!empty($attendee->promo_code_id)) {
                $promo = PromoCode::find($attendee->promo_code_id);
                if ($promo) {
                    $promo->increment('uses_count');
                }
            }

            Mail::to($attendee->email)->send(new TicketConfirmationMail($attendee));
        }

        return redirect()->route('checkout.success', $order);
    }

    public function success(Order $order)
    {
        $order->load('attendees');
        return view('checkout.success', compact('order'));
    }
}