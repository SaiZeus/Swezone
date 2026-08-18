@extends('layouts.master')

@section('title', 'MMQR Payment - Order #' . $order->order_number)

@section('content')
<section class="payment-section pt-120 pb-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="card p-4 shadow-sm border-0">
                    <h3 class="mb-3">CB Bank MMQR Payment</h3>
                    <p class="text-muted">Scan the QR code using your CB Pay or any MMQR-supported banking app to complete your ticket registration.</p>

                    <div class="my-4">
                        <!-- MMQR Placeholder Image -->
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=MMQR_ORDER_{{ $order->order_number }}_AMT_{{ $order->total_amount }}" 
                             alt="MMQR Code" class="img-fluid border p-2 rounded">
                    </div>

                    <div class="mb-3">
                        <p class="mb-1"><strong>Order Ref:</strong> {{ $order->order_number }}</p>
                        <h4 class="text-success">Total: ${{ number_format($order->total_amount, 2) }}</h4>
                    </div>

                    <form action="{{ route('checkout.complete', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg w-100">Done (Simulate Paid)</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection