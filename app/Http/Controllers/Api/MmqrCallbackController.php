<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Mail\TicketConfirmationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Exception;

class MmqrCallbackController extends Controller
{
    /**
     * CB Bank MMQR Callback Handler
     * URL: https://swezon.com.mm/api/payment/mmqr/callback
     */
    public function handleCallback(Request $request)
    {
        Log::info('MMQR Callback Received', $request->all());

        $referenceNo     = $request->input('referenceNo');
        $transStatusCode  = $request->input('transStatusCode');
        $qrReferenceNo   = $request->input('qrReferenceNo');

        if (!$referenceNo) {
            return response()->json([
                'qrReferenceNo' => $qrReferenceNo,
                'returnCode'    => '0014',
                'message'       => 'Missing reference number.',
            ], 400);
        }

        try {
            return DB::transaction(function () use ($request, $referenceNo, $transStatusCode, $qrReferenceNo) {
                // Lock the order row to prevent race conditions during concurrent callbacks
                $order = Order::with('attendees.ticketCategory.event')
                    ->where('order_number', $referenceNo)
                    ->lockForUpdate()
                    ->first();

                if (!$order) {
                    return response()->json([
                        'qrReferenceNo' => $qrReferenceNo,
                        'returnCode'    => '0006',
                        'message'       => 'Invalid reference number.',
                    ], 404);
                }

                if ($transStatusCode === 'SUCCESS') {
                    // Prevent duplicate execution if webhook is resent
                    if ($order->payment_status === 'paid') {
                        return response()->json([
                            'qrReferenceNo' => $qrReferenceNo,
                            'returnCode'    => '0000',
                            'message'       => 'The transaction is succeeded',
                        ]);
                    }

                    // Check ticket capacity to handle race conditions (last ticket edge case)
                    foreach ($order->attendees as $attendee) {
                        $category = $attendee->ticketCategory;
                        if ($category && $category->capacity !== null) {
                            if (($category->tickets_sold + 1) > $category->capacity) {
                                // Overbook condition detected: Flag order for manual refund/support review
                                $order->update([
                                    'payment_status' => 'refund_required',
                                    'transaction_id' => $request->input('transactionId'),
                                ]);

                                Log::warning("MMQR Overbook Detected for Order {$referenceNo}. Flagged for refund.");

                                return response()->json([
                                    'qrReferenceNo' => $qrReferenceNo,
                                    'returnCode'    => '0007',
                                    'message'       => 'Ticket capacity exceeded. Order flagged for refund.',
                                ]);
                            }
                        }
                    }

                    // Complete payment update
                    $order->update([
                        'payment_status' => 'paid',
                        'payment_method' => 'MMQR',
                        'transaction_id' => $request->input('transactionId'),
                    ]);

                    // Increment tickets sold and attempt mail dispatch safely
                    foreach ($order->attendees as $attendee) {
                        if ($attendee->ticketCategory) {
                            $attendee->ticketCategory->increment('tickets_sold');
                        }

                        // Wrap mail in try-catch so SMTP timeouts don't block callback response
                        try {
                            Mail::to($attendee->email)->send(new TicketConfirmationMail($attendee));
                        } catch (Exception $mailEx) {
                            Log::error("Failed to send ticket email to {$attendee->email}: " . $mailEx->getMessage());
                        }
                    }

                    return response()->json([
                        'qrReferenceNo' => $qrReferenceNo,
                        'returnCode'    => '0000',
                        'message'       => 'The transaction is succeeded',
                    ]);
                }

                // Payment status is not SUCCESS
                $order->update(['payment_status' => 'failed']);

                return response()->json([
                    'qrReferenceNo' => $qrReferenceNo,
                    'returnCode'    => '0007',
                    'message'       => 'Transaction failed',
                ]);
            });
        } catch (Exception $e) {
            Log::error('MMQR Callback Transaction Exception', ['error' => $e->getMessage()]);

            return response()->json([
                'qrReferenceNo' => $qrReferenceNo,
                'returnCode'    => '9999',
                'message'       => 'Internal server error processing callback.',
            ], 500);
        }
    }
}