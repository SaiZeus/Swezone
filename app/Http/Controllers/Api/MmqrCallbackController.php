<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MmqrCallbackController extends Controller
{
    /**
     * CB Bank MMQR Callback Handler
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

        $order = Order::where('order_number', $referenceNo)->first();

        if (!$order) {
            return response()->json([
                'qrReferenceNo' => $qrReferenceNo,
                'returnCode'    => '0006',
                'message'       => 'Invalid reference number.',
            ], 404);
        }

        if ($transStatusCode === 'SUCCESS') {
            $order->update([
                'status'         => 'paid',
                'payment_method' => 'MMQR',
                'transaction_id' => $request->input('transactionId'),
            ]);

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