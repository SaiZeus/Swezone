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

    /* =========================================================
       MYANMARPAY OFFICIAL MMQR CARD DESIGN (20:29 ASPECT RATIO)
       ========================================================= */
    .mmqr-card-wrapper {
        width: 100%;
        max-width: 310px;
        margin: 0 auto;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        font-family: Arial, sans-serif;
    }

    .mmqr-card-header {
        position: relative;
        padding: 16px 12px 8px;
        text-align: center;
    }

    .mmqr-logo {
        height: 38px;
        width: auto;
        margin-bottom: 6px;
    }

    .mmqr-divider-line {
        height: 2px;
        background: #FBD913;
        width: 100%;
        margin: 6px 0;
    }

    .mmqr-receiver-name {
        font-size: 11px;
        color: #475569;
        margin: 4px 0 2px;
        font-weight: 600;
    }

    .mmqr-amount {
        font-size: 20px;
        font-weight: 800;
        color: #000000;
        margin: 0;
    }

    .mmqr-amount span {
        font-size: 11px;
        font-weight: 700;
        color: #334155;
    }

    .mmqr-body {
        padding: 12px 18px;
        text-align: center;
    }

    .mmqr-label {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.05em;
        color: #1e293b;
        margin-bottom: 6px;
    }

    .mmqr-image-container {
        width: 100%;
        background: #ffffff;
        padding: 10px;
        border-radius: 8px;
        display: inline-block;
    }

    .mmqr-image-container img {
        width: 100%;
        height: auto;
        display: block;
    }

    .mmqr-footer-bank {
        padding-bottom: 12px;
        text-align: center;
    }

    .mmqr-bank-tag {
        font-size: 10px;
        font-weight: 800;
        color: #17479E;
        letter-spacing: 0.05em;
    }

    .order-info-card {
        background: #faf5ff;
        border: 1px solid #e9d5ff;
        border-radius: 14px;
        padding: 16px 20px;
    }

    .payment-btn {
        min-height: 46px;
        border: 0;
        border-radius: 12px;
        background: linear-gradient(135deg, #c084fc 0%, #a855f7 100%);
        box-shadow: 0 6px 18px rgba(168, 85, 247, .3);
        font-size: .92rem;
        font-weight: 800;
        color: #fff !important;
        transition: transform .15s ease, box-shadow .15s ease;
    }

    .payment-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 22px rgba(168, 85, 247, .4);
    }

    .polling-indicator {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 0.8rem;
        font-weight: 700;
        color: #7e22ce;
    }
</style>

<section class="payment-section pt-60 pb-60">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="payment-card">
                    <h3 class="mb-2"><i class="fas fa-qrcode me-2 text-purple"></i>CB Bank MMQR Payment</h3>
                    <p class="text-muted mb-4">Scan the QR code using your CB Pay or any MMQR-supported banking app to complete your ticket registration.</p>

                    @if(!empty($mmqrData['qr_code_info']))
                        {{-- OFFICIAL MYANMARPAY MMQR DISPLAY CARD --}}
                        <div class="mmqr-card-wrapper my-3">
                            <div class="mmqr-card-header">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c5/MyanmarPay_Logo.jpg/320px-MyanmarPay_Logo.jpg" 
                                     alt="MyanmarPay" class="mmqr-logo" onerror="this.style.display='none'">
                                <div class="mmqr-divider-line"></div>
                                <p class="mmqr-receiver-name">Swezon Group</p>
                                <h4 class="mmqr-amount">{{ number_format($order->total_amount) }} <span>MMK</span></h4>
                            </div>

                            <div class="mmqr-body">
                                <div class="mmqr-label">MMQR</div>
                                <div class="mmqr-image-container">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($mmqrData['qr_code_info']) }}" 
                                         alt="MMQR Code">
                                </div>
                            </div>

                            <div class="mmqr-footer-bank">
                                <span class="mmqr-bank-tag"><i class="fas fa-university me-1"></i> CB BANK</span>
                            </div>
                        </div>
                    @else
                        {{-- FALLBACK QR IF API ERROR ENCOUNTERED --}}
                        <div class="alert alert-warning py-2 small mb-3">
                            <i class="fas fa-exclamation-triangle me-1"></i> Temporary gateway issue. Showing backup QR payload.
                        </div>
                        <div class="mmqr-card-wrapper my-3">
                            <div class="mmqr-body">
                                <div class="mmqr-image-container">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=MMQR_ORDER_{{ $order->order_number }}_AMT_{{ $order->total_amount }}" 
                                         alt="MMQR Code">
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="polling-indicator my-3">
                        <div class="spinner-border spinner-border-sm" role="status"></div>
                        <span>Waiting for payment confirmation...</span>
                    </div>

                    <div class="order-info-card my-4">
                        <p class="mb-1 font-weight-bold">Order Ref: <strong>{{ $order->order_number }}</strong></p>
                        <h4 class="mb-0">Total Amount: {{ number_format($order->total_amount) }} MMK</h4>
                    </div>

                    {{-- SIMULATE PAID / COMPLETE BUTTON FOR TESTING --}}
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

{{-- REAL-TIME AUTO-REDIRECT POLLING --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const orderId = "{{ $order->id }}";
        const successUrl = "{{ route('checkout.success', $order->id) }}";

        // Poll server every 3 seconds to check if callback updated order to 'paid'
        const interval = setInterval(function () {
            fetch(`/api/check-order-status/${orderId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'paid') {
                        clearInterval(interval);
                        window.location.href = successUrl;
                    }
                })
                .catch(err => console.error("Error polling order status:", err));
        }, 3000);
    });
</script>

@endsection