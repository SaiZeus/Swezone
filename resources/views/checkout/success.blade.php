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
       HIDDEN TICKET RENDERING CONTAINER
       ========================================================= */
    .hidden-ticket-container {
        position: absolute;
        left: -9999px;
        top: -9999px;
        visibility: visible;
        opacity: 0;
        pointer-events: none;
    }

    .ticket-wrapper {
        position: relative;
        width: 1600px;
        height: 517px;
    }

    .ticket-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 1600px;
        height: 517px;
    }

    .qr-box {
        position: absolute;
        top: 126px;
        left: 950px;
        width: 310px;
        height: 310px;
    }

    .qr-box img {
        width: 100%;
        height: 100%;
        display: block;
    }
    
    /* Ticket Number Area */
    .ticket-number-area {
        position: absolute;
        top: 300px;
        left: 1390px;
        width: 60px;
        height: 360px;
    }

    .ticket-number {
        font-size: 24px;
        font-weight: 800;
        color: #000000;
        -webkit-transform: rotate(270deg);
        transform: rotate(270deg);
        -webkit-transform-origin: top left;
        transform-origin: top left;
        position: absolute;
        top: 0;
        left: 0;
        white-space: nowrap;
    }

    /* Name & Phone Area */
    .buyer-data-area {
        position: absolute;
        top: 400px;
        left: 1520px;
        width: 60px;
        height: 500px;
    }

    .buyer-info-group {
        -webkit-transform: rotate(270deg);
        transform: rotate(270deg);
        -webkit-transform-origin: top left;
        transform-origin: top left;
        position: absolute;
        top: 0;
        left: 0;
        white-space: nowrap;
    }

    .buyer-name {
        font-size: 20px;
        font-weight: 700;
        color: #FFFFFF;
        text-transform: uppercase;
        display: block;
        margin-bottom: 10px;
    }

    .buyer-phone {
        font-size: 18px;
        font-weight: 600;
        color: #FFFFFF;
        display: block;
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
                 INFORMATION DETAILS
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
                    <span>Download Ticket</span>
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

{{-- Hidden Ticket Render Nodes synced with backend fields --}}
<div class="hidden-ticket-container" id="ticket-nodes-wrapper">
    @foreach($order->attendees as $index => $attendee)
        @php
            // Pull backend fields: BIB Number -> Ticket Code -> Fallback Sequence
            $ticketRef = $attendee->bib_number 
                ?? $attendee->ticket_code 
                ?? ('BGR26' . str_pad($attendee->id, 4, '0', STR_PAD_LEFT));
        @endphp
        <div class="ticket-wrapper" id="render-ticket-node-{{ $index }}">
            <img src="{{ asset('assets/img/ticket/ticket.jpg') }}" class="ticket-bg" alt="Ticket" crossorigin="anonymous">

            <div class="qr-box">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=310x310&data={{ urlencode($ticketRef) }}" alt="QR Code" crossorigin="anonymous">
            </div>

            <!-- Ticket Number Area -->
            <div class="ticket-number-area">
                <div class="ticket-number">
                    {{ $ticketRef }}
                </div>
            </div>

            <!-- Name & Phone Area -->
            <div class="buyer-data-area">
                <div class="buyer-info-group">
                    <span class="buyer-name">{{ $attendee->full_name ?? '' }}</span>
                    <span class="buyer-phone">{{ $attendee->phone ?? '' }}</span>
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- Include html2canvas, jspdf, and JSZip CDNs --}}
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.2/jszip.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const downloadBtn = document.getElementById('download-receipt-btn');
    const attendeeCount = {{ $order->attendees->count() }};

    if (downloadBtn) {
        const spanEl = downloadBtn.querySelector('span');
        if (spanEl) {
            spanEl.textContent = attendeeCount > 1 ? 'Download All Tickets (ZIP)' : 'Download Ticket';
        }

        downloadBtn.addEventListener('click', async function () {
            const originalHtml = downloadBtn.innerHTML;
            downloadBtn.style.pointerEvents = 'none';

            try {
                const attendees = @json($order->attendees);
                const { jsPDF } = window.jspdf;

                if (attendeeCount === 1) {
                    downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Generating Ticket PDF...</span>';
                    const ticketNode = document.getElementById('render-ticket-node-0');
                    
                    // --- DEBUG LOGGING ---
                    console.log('--- DEBUG: Single Ticket Node ---', ticketNode);
                    if (ticketNode) {
                        console.log('Node innerHTML length:', ticketNode.innerHTML.length);
                        console.log('Node children count:', ticketNode.children.length);
                    }
                    // ---------------------

                    const canvas = await html2canvas(ticketNode, {
                        scale: 2,
                        useCORS: true,
                        allowTaint: false,
                        backgroundColor: '#ffffff'
                    });

                    const imgData = canvas.toDataURL('image/jpeg', 0.95);
                    const pdf = new jsPDF({
                        orientation: 'landscape',
                        unit: 'px',
                        format: [canvas.width, canvas.height]
                    });
                    pdf.addImage(imgData, 'JPEG', 0, 0, canvas.width, canvas.height);

                    const ticketNumText = ticketNode.querySelector('.ticket-number').textContent.trim();
                    const sanitizedName = attendees[0].full_name ? attendees[0].full_name.replace(/[^a-zA-Z0-9]/g, '_') : 'Attendee';
                    pdf.save(`Ticket_${ticketNumText}_${sanitizedName}.pdf`);
                } else {
                    const zip = new JSZip();

                    for (let i = 0; i < attendees.length; i++) {
                        downloadBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> <span>Packing PDF (${i + 1}/${attendeeCount})...</span>`;
                        
                        const ticketNode = document.getElementById('render-ticket-node-' + i);
                        
                        // --- DEBUG LOGGING ---
                        console.log(`--- DEBUG: Ticket Node ${i} ---`, ticketNode);
                        if (ticketNode) {
                            console.log(`Node ${i} innerHTML length:`, ticketNode.innerHTML.length);
                        }
                        // ---------------------

                        const canvas = await html2canvas(ticketNode, {
                            scale: 2,
                            useCORS: true,
                            allowTaint: false,
                            backgroundColor: '#ffffff'
                        });

                        const imgData = canvas.toDataURL('image/jpeg', 0.95);
                        const pdf = new jsPDF({
                            orientation: 'landscape',
                            unit: 'px',
                            format: [canvas.width, canvas.height]
                        });
                        pdf.addImage(imgData, 'JPEG', 0, 0, canvas.width, canvas.height);

                        const pdfBlob = pdf.output('blob');
                        const ticketNumText = ticketNode.querySelector('.ticket-number').textContent.trim();
                        const sanitizedName = attendees[i].full_name ? attendees[i].full_name.replace(/[^a-zA-Z0-9]/g, '_') : `Attendee_${i+1}`;
                        
                        zip.file(`Ticket_${ticketNumText}_${sanitizedName}.pdf`, pdfBlob);
                    }

                    downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Compiling ZIP...</span>';
                    const content = await zip.generateAsync({ type: 'blob' });
                    
                    const link = document.createElement('a');
                    link.href = URL.createObjectURL(content);
                    link.download = 'Swezon_Tickets_{{ $order->order_number }}.zip';
                    link.click();
                    URL.revokeObjectURL(link.href);
                }
            } catch (err) {
                console.error('Download processing error:', err);
                alert('Failed to package ticket PDFs. Please try again.');
            } finally {
                downloadBtn.innerHTML = originalHtml;
                downloadBtn.style.pointerEvents = 'auto';
            }
        });
    }
});
</script>
@endpush

@endsection