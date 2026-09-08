@extends('layouts.master')

@section('title', 'Payment Confirmed - Order ' . $order->order_number)

@section('content')

<style>
    /* =========================================================
       SWEZON E-RECEIPT
       Purple Celebration Theme
       ========================================================= */

    .swezon-receipt-section {
        position: relative;
        min-height: 100vh;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 55px 15px;
        background:
            radial-gradient(circle at 10% 10%, rgba(168, 85, 247, 0.35), transparent 25%),
            radial-gradient(circle at 90% 15%, rgba(192, 132, 252, 0.30), transparent 25%),
            radial-gradient(circle at 50% 100%, rgba(124, 58, 237, 0.25), transparent 35%),
            linear-gradient(145deg, #f5f3ff 0%, #ede9fe 45%, #e9d5ff 100%);
    }

    /* Background glow */
    .swezon-receipt-section::before {
        content: "";
        position: absolute;
        width: 500px;
        height: 500px;
        top: -250px;
        left: -200px;
        border-radius: 50%;
        background: rgba(139, 92, 246, 0.18);
        filter: blur(60px);
        pointer-events: none;
    }

    .swezon-receipt-section::after {
        content: "";
        position: absolute;
        width: 450px;
        height: 450px;
        right: -200px;
        bottom: -200px;
        border-radius: 50%;
        background: rgba(192, 132, 252, 0.20);
        filter: blur(60px);
        pointer-events: none;
    }

    /* =========================================================
       CONFETTI
       ========================================================= */

    .receipt-confetti {
        position: absolute;
        inset: 0;
        overflow: hidden;
        pointer-events: none;
    }

    .receipt-confetti span {
        position: absolute;
        top: -30px;
        width: 9px;
        height: 18px;
        border-radius: 3px;
        opacity: .75;
        animation: receiptConfettiFall 8s linear infinite;
    }

    .receipt-confetti span:nth-child(1) {
        left: 8%;
        background: #7c3aed;
        transform: rotate(20deg);
        animation-delay: .2s;
    }

    .receipt-confetti span:nth-child(2) {
        left: 19%;
        background: #c084fc;
        transform: rotate(-25deg);
        animation-delay: 2s;
    }

    .receipt-confetti span:nth-child(3) {
        left: 32%;
        background: #f59e0b;
        transform: rotate(15deg);
        animation-delay: 4s;
    }

    .receipt-confetti span:nth-child(4) {
        left: 48%;
        background: #a855f7;
        transform: rotate(-15deg);
        animation-delay: 1s;
    }

    .receipt-confetti span:nth-child(5) {
        left: 64%;
        background: #8b5cf6;
        transform: rotate(30deg);
        animation-delay: 3s;
    }

    .receipt-confetti span:nth-child(6) {
        left: 77%;
        background: #fbbf24;
        transform: rotate(-20deg);
        animation-delay: 5s;
    }

    .receipt-confetti span:nth-child(7) {
        left: 90%;
        background: #c084fc;
        transform: rotate(25deg);
        animation-delay: 2.5s;
    }

    @keyframes receiptConfettiFall {
        0% {
            transform: translateY(-50px) rotate(0deg);
        }

        100% {
            transform: translateY(110vh) rotate(720deg);
        }
    }

    /* =========================================================
       RECEIPT CARD
       ========================================================= */

    .swezon-receipt {
        position: relative;
        width: 100%;
        max-width: 780px;
        background: rgba(255, 255, 255, .96);
        border: 1px solid rgba(196, 181, 253, .65);
        border-radius: 18px;
        box-shadow:
            0 30px 80px rgba(76, 29, 149, .18),
            0 10px 30px rgba(124, 58, 237, .10);
        overflow: hidden;
        z-index: 5;
    }

    /* =========================================================
       HEADER
       ========================================================= */

    .receipt-header {
        min-height: 120px;
        padding: 25px 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        background: linear-gradient(
            135deg,
            #ffffff 0%,
            #faf5ff 100%
        );
        border-bottom: 1px solid #ede9fe;
    }

    .receipt-logo {
        display: block;
        width: auto;
        height: 75px;
        max-width: 260px;
        object-fit: contain;
        object-position: left center;
    }

    .receipt-title {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 10px 17px;
        border-radius: 999px;
        background: #f3e8ff;
        border: 1px solid #e9d5ff;
        color: #6d28d9;
        font-size: 15px;
        font-weight: 800;
        white-space: nowrap;
    }

    .receipt-title i {
        font-size: 15px;
    }

    /* =========================================================
       MAIN SUCCESS AREA
       ========================================================= */

    .receipt-main {
        padding: 42px 55px 40px;
        text-align: center;
    }

    .success-icon {
        position: relative;
        width: 72px;
        height: 72px;
        margin: 0 auto 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: linear-gradient(135deg, #a855f7, #6d28d9);
        color: #fff;
        font-size: 30px;
        box-shadow:
            0 10px 30px rgba(124, 58, 237, .28),
            0 0 0 8px rgba(233, 213, 255, .65);
        animation: successPulse 2.5s ease-in-out infinite;
    }

    @keyframes successPulse {
        0%, 100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }
    }

    .success-icon::before {
        content: "✦";
        position: absolute;
        top: -19px;
        right: -25px;
        color: #f59e0b;
        font-size: 21px;
        animation: sparkle 1.8s ease-in-out infinite;
    }

    .success-icon::after {
        content: "✦";
        position: absolute;
        bottom: -15px;
        left: -25px;
        color: #c084fc;
        font-size: 18px;
        animation: sparkle 1.8s ease-in-out infinite .5s;
    }

    @keyframes sparkle {
        0%, 100% {
            opacity: .35;
            transform: scale(.7) rotate(0deg);
        }

        50% {
            opacity: 1;
            transform: scale(1.3) rotate(45deg);
        }
    }

    .congratulations-title {
        margin: 0 0 10px;
        color: #581c87;
        font-size: clamp(2rem, 5vw, 3rem);
        line-height: 1.1;
        font-weight: 850;
        letter-spacing: -.04em;
    }

    .success-message {
        margin: 0 auto 30px;
        color: #7e22ce;
        font-size: 1rem;
        font-weight: 600;
        line-height: 1.6;
    }

    /* =========================================================
       AMOUNT
       ========================================================= */

    .amount-box {
        margin: 0 auto 30px;
        padding: 24px 20px 22px;
        border-radius: 16px;
        background:
            linear-gradient(
                135deg,
                #faf5ff 0%,
                #f3e8ff 100%
            );
        border: 1px solid #e9d5ff;
    }

    .amount-label {
        margin-bottom: 5px;
        color: #8b5cf6;
        font-size: .82rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .12em;
    }

    .amount-value {
        color: #5b21b6;
        font-size: clamp(2rem, 5vw, 2.8rem);
        line-height: 1.15;
        font-weight: 850;
        letter-spacing: -.035em;
    }

    .amount-currency {
        font-size: 1rem;
        font-weight: 700;
        color: #7c3aed;
        margin-left: 4px;
    }

    /* =========================================================
       DETAILS
       ========================================================= */

    .receipt-details {
        margin: 0 auto;
        border: 1px solid #e9d5ff;
        border-radius: 14px;
        overflow: hidden;
        background: #ffffff;
        text-align: left;
    }

    .receipt-detail-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 25px;
        min-height: 62px;
        padding: 12px 20px;
        border-bottom: 1px solid #f1e8ff;
    }

    .receipt-detail-row:last-child {
        border-bottom: none;
    }

    .receipt-detail-label {
        color: #8b5cf6;
        font-size: .9rem;
        font-weight: 600;
    }

    .receipt-detail-value {
        color: #3b0764;
        font-size: .95rem;
        font-weight: 750;
        text-align: right;
    }

    .order-number {
        color: #6d28d9;
        background: #f5f3ff;
        border-radius: 6px;
        padding: 5px 9px;
    }

    .attendee-count {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: #6d28d9;
    }

    .attendee-count i {
        font-size: 13px;
    }

    /* =========================================================
       RECEIPT CUT LINE
       ========================================================= */

    .receipt-cut-line {
        position: relative;
        height: 26px;
        border-top: 2px dashed #c4b5fd;
        margin: 0 28px;
    }

    .receipt-cut-line::before,
    .receipt-cut-line::after {
        content: "";
        position: absolute;
        top: -14px;
        width: 27px;
        height: 27px;
        background: #ede9fe;
        border-radius: 50%;
    }

    .receipt-cut-line::before {
        left: -42px;
    }

    .receipt-cut-line::after {
        right: -42px;
    }

    /* =========================================================
       FOOTER
       ========================================================= */

    .receipt-footer {
        position: relative;
        padding: 25px 35px 32px;
        text-align: center;
        background:
            linear-gradient(
                180deg,
                #ffffff 0%,
                #faf5ff 100%
            );
    }

    .thank-you {
        margin: 0 0 7px;
        color: #581c87;
        font-size: 1.25rem;
        font-weight: 800;
    }

    .thank-you-subtitle {
        margin: 0 0 24px;
        color: #8b5cf6;
        font-size: .88rem;
        font-weight: 600;
    }

    .receipt-actions {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    /* =========================================================
       BUTTONS
       ========================================================= */

    .btn-swezon-home {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        min-height: 48px;
        padding: 0 24px;
        border-radius: 12px;
        background: linear-gradient(135deg, #8b5cf6, #6d28d9);
        border: none;
        color: #ffffff !important;
        font-size: .9rem;
        font-weight: 750;
        text-decoration: none;
        box-shadow: 0 8px 22px rgba(109, 40, 217, .22);
        transition: all .2s ease;
    }

    .btn-swezon-home:hover {
        color: #ffffff !important;
        transform: translateY(-2px);
        background: linear-gradient(135deg, #7c3aed, #581c87);
        box-shadow: 0 12px 28px rgba(109, 40, 217, .32);
    }

    .btn-swezon-download {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        min-height: 48px;
        padding: 0 24px;
        border-radius: 12px;
        background: #ffffff;
        border: 2px solid #8b5cf6;
        color: #7c3aed !important;
        font-size: .9rem;
        font-weight: 750;
        text-decoration: none;
        box-shadow: 0 4px 14px rgba(139, 92, 246, .12);
        transition: all .2s ease;
    }

    .btn-swezon-download:hover {
        background: #f5f3ff;
        color: #6d28d9 !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(139, 92, 246, .2);
    }

    /* =========================================================
       BOTTOM DECORATIVE WAVES
       ========================================================= */

    .receipt-waves {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 90px;
        overflow: hidden;
        pointer-events: none;
        opacity: .6;
    }

    .receipt-waves::before {
        content: "";
        position: absolute;
        width: 110%;
        height: 90px;
        left: -5%;
        bottom: -55px;
        border-radius: 50%;
        background: rgba(139, 92, 246, .15);
        transform: rotate(-2deg);
    }

    .receipt-waves::after {
        content: "";
        position: absolute;
        width: 110%;
        height: 70px;
        left: -5%;
        bottom: -50px;
        border-radius: 50%;
        background: rgba(192, 132, 252, .18);
        transform: rotate(2deg);
    }

    /* =========================================================
       MOBILE
       ========================================================= */

    @media (max-width: 767.98px) {

        .swezon-receipt-section {
            padding: 30px 12px;
            align-items: flex-start;
        }

        .swezon-receipt {
            border-radius: 14px;
        }

        .receipt-header {
            min-height: 95px;
            padding: 18px 18px;
        }

        .receipt-logo {
            height: 55px;
            max-width: 180px;
        }

        .receipt-title {
            padding: 7px 11px;
            font-size: 12px;
        }

        .receipt-title i {
            font-size: 12px;
        }

        .receipt-main {
            padding: 35px 18px 30px;
        }

        .success-icon {
            width: 62px;
            height: 62px;
            font-size: 25px;
            margin-bottom: 18px;
        }

        .congratulations-title {
            font-size: 2rem;
        }

        .success-message {
            font-size: .9rem;
            margin-bottom: 25px;
        }

        .amount-box {
            padding: 20px 15px;
            margin-bottom: 22px;
        }

        .amount-value {
            font-size: 2rem;
        }

        .receipt-detail-row {
            min-height: 58px;
            padding: 11px 14px;
            gap: 12px;
        }

        .receipt-detail-label {
            font-size: .82rem;
        }

        .receipt-detail-value {
            font-size: .86rem;
        }

        .receipt-cut-line {
            margin: 0 20px;
        }

        .receipt-footer {
            padding: 22px 18px 28px;
        }

        .thank-you {
            font-size: 1.1rem;
        }

        .receipt-actions {
            flex-direction: column;
            gap: 10px;
        }

        .btn-swezon-home,
        .btn-swezon-download {
            width: 100%;
        }
    }
</style>


<section class="swezon-receipt-section" id="receipt-capture-area">

    {{-- Floating Confetti --}}
    <div class="receipt-confetti">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>


    {{-- E-Receipt --}}
    <div class="swezon-receipt">

        {{-- =====================================================
             HEADER
             ===================================================== --}}
        <div class="receipt-header">

            <img
                src="{{ asset('assets/img/logo/Swezon_Logo1.1V.png') }}"
                alt="Swezon"
                class="receipt-logo"
            >

            <div class="receipt-title">
                <i class="fas fa-receipt"></i>
                <span>E-Receipt</span>
            </div>

        </div>


        {{-- =====================================================
             MAIN CONTENT
             ===================================================== --}}
        <div class="receipt-main">

            {{-- Success Icon --}}
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>

            {{-- Congratulations --}}
            <h1 class="congratulations-title">
                Congratulations!
            </h1>

            <p class="success-message">
                Your payment has been successfully completed.
            </p>


            {{-- Amount --}}
            <div class="amount-box">

                <div class="amount-label">
                    Total Amount
                </div>

                <div class="amount-value">
                    {{ number_format($order->total_amount, 2) }}
                    <span class="amount-currency">MMK</span>
                </div>

            </div>


            {{-- =================================================
                 ONLY THE INFORMATION YOU NEED
                 ================================================= --}}
            <div class="receipt-details">

                {{-- Order Number --}}
                <div class="receipt-detail-row">

                    <span class="receipt-detail-label">
                        Order Number
                    </span>

                    <span class="receipt-detail-value order-number">
                        #{{ $order->order_number }}
                    </span>

                </div>


                {{-- Amount --}}
                <div class="receipt-detail-row">

                    <span class="receipt-detail-label">
                        Amount
                    </span>

                    <span class="receipt-detail-value">
                        {{ number_format($order->total_amount, 2) }} MMK
                    </span>

                </div>


                {{-- Attendees --}}
                <div class="receipt-detail-row">

                    <span class="receipt-detail-label">
                        Number of Attendees
                    </span>

                    <span class="receipt-detail-value attendee-count">
                        <i class="fas fa-users"></i>
                        {{ $order->attendees->count() }}
                        {{ $order->attendees->count() == 1 ? 'Attendee' : 'Attendees' }}
                    </span>

                </div>

            </div>

        </div>


        {{-- Receipt Cut Line --}}
        <div class="receipt-cut-line"></div>


        {{-- =====================================================
             FOOTER
             ===================================================== --}}
        <div class="receipt-footer">

            <p class="thank-you">
                Thank you for using Swezon!
            </p>

            <p class="thank-you-subtitle">
                Your registration has been successfully confirmed.
            </p>

            <div class="receipt-actions">
                <button type="button" id="download-receipt-btn" class="btn-swezon-download">
                    <i class="fas fa-download"></i>
                    <span>Download Image</span>
                </button>

                <a
                    href="{{ route('home') }}"
                    class="btn-swezon-home"
                >
                    <i class="fas fa-home"></i>
                    <span>Return to Home</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

        </div>

    </div>


    {{-- Decorative Bottom Waves --}}
    <div class="receipt-waves"></div>

</section>

{{-- Include html2canvas CDN for image generation --}}
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const downloadBtn = document.getElementById('download-receipt-btn');
    if (downloadBtn) {
        downloadBtn.addEventListener('click', function () {
            const receiptElement = document.getElementById('receipt-capture-area');
            
            // Temporarily update button state to indicate loading
            const originalHtml = downloadBtn.innerHTML;
            downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Generating...</span>';
            downloadBtn.style.pointerEvents = 'none';

            html2canvas(receiptElement, {
                scale: 2, // Higher quality image resolution
                useCORS: true,
                allowTaint: true,
                backgroundColor: null
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = 'Swezon_Receipt_{{ $order->order_number }}.png';
                link.href = canvas.toDataURL('image/png');
                link.click();

                // Restore button state
                downloadBtn.innerHTML = originalHtml;
                downloadBtn.style.pointerEvents = 'auto';
            }).catch(err => {
                console.error('Receipt download error:', err);
                downloadBtn.innerHTML = originalHtml;
                downloadBtn.style.pointerEvents = 'auto';
                alert('Failed to download receipt image. Please try again.');
            });
        });
    }
});
</script>
@endpush

@endsection