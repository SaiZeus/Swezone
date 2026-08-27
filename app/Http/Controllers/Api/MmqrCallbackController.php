<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Mail\TicketConfirmationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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

        // Find order with attendees and ticket categories
        $order = Order::with('attendees.ticketCategory.event')->where('order_number', $referenceNo)->first();

        if (!$order) {
            return response()->json([
                'qrReferenceNo' => $qrReferenceNo,
                'returnCode'    => '0006',
                'message'       => 'Invalid reference number.',
            ], 404);
        }

        if ($transStatusCode === 'SUCCESS') {
            // Check if not already paid to prevent duplicate executions
            if ($order->payment_status !== 'paid') {
                $order->update([
                    'payment_status' => 'paid',
                    'payment_method' => 'MMQR',
                    'transaction_id' => $request->input('transactionId'),
                ]);

                // Increment ticket counts and send confirmation emails
                foreach ($order->attendees as $attendee) {
                    if ($attendee->ticketCategory) {
                        $attendee->ticketCategory->increment('tickets_sold');
                    }
                    Mail::to($attendee->email)->send(new TicketConfirmationMail($attendee));
                }
            }

            return response()->json([
                'qrReferenceNo' => $qrReferenceNo,
                'returnCode'    => '0000',
                'message'       => 'The transaction is succeeded',
            ]);
        }

        return response()->json([
            'qrReferenceNo' => $qrReferenceNo,
            'returnCode'    => '0007',
            'message'       => 'Transaction failed',
        ]);
    }
}