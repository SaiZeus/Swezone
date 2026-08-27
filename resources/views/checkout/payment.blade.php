@extends('layouts.master')

@section('title', 'MMQR Payment - Order #' . $order->order_number)

@section('content')

<section class="payment-section pt-60 pb-60" style="background-color: #f8f9fa; min-height: 80vh;">
    <div class="container text-center">
        <h3><strong>CB Bank E-commerce</strong></h3>
        <div align="center" style="width: 100%">
            <br />
            <div style="font: 15px Calibri; padding: 10px 10px 10px 10px; background-color: white;">
                <div style="font: 15px Calibri; font-weight: bold;">
                    Sale
                </div>
                <br />
                <div style="border: 1px solid gray; width: 340px; max-width: 90%; border-bottom: 1px solid #264EA1; margin: 0 auto; background: #fff;">
                    
                    {{-- HEADER BANNER --}}
                    <div align="center" style="height:18.5%">
                        <img src="{{ asset('assets/img/cb/Logo_1080px.png') }}" style="width:100%;" alt="MyanmarPay Header" />
                    </div>

                    {{-- MERCHANT, ORDER NUMBER & AMOUNT DISPLAY --}}
                    <div style="height: auto; text-align: left; border-bottom: 1px dotted #FBD913; padding-bottom: 10px;">
                        <div style="font-size: 13.5px; color: black; text-align: left; padding-left: 12.5%; padding-top: 8px;">
                            Swezon Group
                        </div>
                        <div style="font-size: 11px; color: #555; text-align: left; padding-left: 12.5%; font-weight: bold;">
                            Order Ref: {{ $order->order_number }}
                        </div>
                        <br />
                        <span style="font-size: 27px; color: black; text-align: left; padding-left: 12.5%; font-weight: bold;">
                            {{ number_format($order->total_amount, 2) }}
                        </span>
                        <span style="font-size: 13.5px; color: black; text-align: left;">
                            MMK
                        </span>
                    </div>

                    {{-- MMQR LABEL --}}
                    <div>
                        <br/>
                        <span style="color: black; font-size:20px; font-weight: bold;">MMQR</span>
                    </div>

                    {{-- DYNAMIC QR CODE DISPLAY --}}
                    <div style="padding: 10px 0;">
                        @if(!empty($mmqrData['qr_code_info']))
                            <img id="imgQR" src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($mmqrData['qr_code_info']) }}" style="width:80%; height: auto;" alt="MMQR Code" />
                        @else
                            <img id="imgQR" src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=MMQR_ORDER_{{ $order->order_number }}" style="width:80%; height: auto;" alt="MMQR Code" />
                        @endif
                    </div>

                    {{-- CB BANK FOOTER LOGO --}}
                    <div>
                        <img src="{{ asset('assets/img/cb/logo.png') }}" style="width:20%; min-width: 60px; height: auto;" alt="CB Bank Logo" />
                    </div>

                    <div style="height: 3%; border-bottom-style: solid; color: #264EA1; margin-top: 8px;">
                        <br />
                    </div>
                </div>
                <br />

                {{-- EXPIRATION & COUNTDOWN TIMER --}}
                <div>
                    <span style="font-size: 14px; color: #d9534f; font-weight: bold;">QR will be expired in 3 mins...</span>
                    <br />
                    <h3 class="mt-2"><span id="timer_display" style="color: #264EA1; font-weight: bold;">03:00</span></h3>
                </div>

                {{-- POLLING SPINNER --}}
                <div class="mt-3 text-muted" style="font-size: 13px;">
                    <div class="spinner-border spinner-border-sm text-primary me-1" role="status"></div>
                    Waiting for payment confirmation...
                </div>

                {{-- SIMULATE PAYMENT BUTTON --}}
                <div class="mt-4" style="max-width: 340px; margin: 0 auto;">
                    <form action="{{ route('checkout.complete', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100 py-2" style="font-size: 14px; font-weight: bold;">
                            Done (Simulate Paid)
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 3-MINUTE TIMER & REAL-TIME STATUS POLLING --}}
@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const orderId = "{{ $order->id }}";
        const successUrl = "{{ route('checkout.success', $order->id) }}";
        
        // 1. 3-Minute Countdown Timer (180 seconds)
        let timeLeft = 180;
        const timerDisplay = document.getElementById('timer_display');

        const countdown = setInterval(function () {
            let minutes = Math.floor(timeLeft / 60);
            let seconds = timeLeft % 60;

            minutes = minutes < 10 ? '0' + minutes : minutes;
            seconds = seconds < 10 ? '0' + seconds : seconds;

            if (timerDisplay) {
                timerDisplay.textContent = `${minutes}:${seconds}`;
            }

            if (timeLeft <= 0) {
                clearInterval(countdown);
                if (timerDisplay) {
                    timerDisplay.textContent = "EXPIRED";
                    timerDisplay.style.color = "red";
                }
                alert("This MMQR payment session has expired. Please try placing your order again.");
                window.location.href = "/";
            }
            timeLeft--;
        }, 1000);

        // 2. Poll server every 3 seconds for payment completion callback
        const statusPoll = setInterval(function () {
            fetch(`/api/check-order-status/${orderId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'paid') {
                        clearInterval(statusPoll);
                        clearInterval(countdown);
                        window.location.href = successUrl;
                    }
                })
                .catch(err => console.error("Polling error:", err));
        }, 3000);
    });
</script>
@endpush

@endsection