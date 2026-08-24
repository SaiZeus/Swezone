@extends('layouts.master')

@section('title', 'MMQR Payment - Order #' . $order->order_number)

@section('content')

<style>
    .payment-section {
        position: relative;
        min-height: 100vh;
        background:
            radial-gradient(circle at 8% 5%, rgba(192, 132, 252, .18), transparent 28%),
            radial-gradient(circle at 92% 12%, rgba(216, 180, 254, .15), transparent 25%),
            linear-gradient(180deg, #faf5ff 0%, #f3e8ff 100%);
        color: #3b0764;
    }

    .payment-card {
        background: rgba(255, 255, 255, .95);
        border: 1px solid #e9d5ff;
        border-radius: 22px !important;
        padding: 36px 28px;
        box-shadow: 0 12px 35px rgba(147, 51, 234, .08);
        backdrop-filter: blur(6px);
    }

    .payment-card h3 {
        color: #581c87;
        font-size: clamp(1.4rem, 2.5vw, 1.8rem);
        font-weight: 800;
        letter-spacing: -.02em;
    }

    .payment-card .text-muted {
        color: #7e22ce !important;
        font-size: .88rem;
        line-height: 1.6;
    }

    .qr-container-box {
        display: inline-block;
        padding: 16px;
        background: #ffffff;
        border: 1px solid #e9d5ff;
        border-radius: 18px;
        box-shadow: 0 6px 20px rgba(147, 51, 234, .06);
    }

    .qr-container-box img {
        max-width: 230px;
        height: auto;
        border-radius: 10px;
    }

    .order-info-card {
        background: #faf5ff;
        border: 1px solid #e9d5ff;
        border-radius: 14px;
        padding: 16px 20px;
    }

    .order-info-card p {
        color: #6b21a8;
        font-size: .88rem;
    }

    .order-info-card h4 {
        color: #581c87;
        font-size: 1.35rem;
        font-weight: 850;
    }

    .payment-btn {
        min-height: 46px;
        border: 0;
        border-radius: 12px;
        background: linear-gradient(135deg, #c084fc 0%, #a855f7 100%);
        box-shadow: 0 6px 18px rgba(168, 85, 247, .3);
        font-size: .92rem;
        font-weight: 800;
        letter-spacing: .01em;
        transition: transform .15s ease, box-shadow .15s ease;
        color: #fff !important;
    }

    .payment-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 22px rgba(168, 85, 247, .4);
    }

    @media (max-width: 767.98px) {
        .payment-section {
            padding-top: 40px !important;
            padding-bottom: 40px !important;
        }

        .payment-card {
            padding: 24px 18px;
        }
    }
</style>

<section class="payment-section pt-60 pb-60">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="payment-card">
                    <h3 class="mb-2"><i class="fas fa-qrcode me-2 text-purple"></i>CB Bank MMQR Payment</h3>
                    <p class="text-muted mb-4">Scan the QR code using your CB Pay or any MMQR-supported banking app to complete your ticket registration.</p>

                    <div class="my-3">
                        <!-- MMQR Image Frame -->
                        <div class="qr-container-box">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=MMQR_ORDER_{{ $order->order_number }}_AMT_{{ $order->total_amount }}" 
                                 alt="MMQR Code" class="img-fluid">
                        </div>
                    </div>

                    <div class="order-info-card my-4">
                        <p class="mb-1 font-weight-bold">Order Ref: <strong>{{ $order->order_number }}</strong></p>
                        <h4 class="mb-0">Total Amount: ${{ number_format($order->total_amount, 2) }}</h4>
                    </div>

                    <form action="{{ route('checkout.complete', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn payment-btn w-100">
                            Done (Simulate Paid) <i class="fas fa-check-circle ms-1"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection