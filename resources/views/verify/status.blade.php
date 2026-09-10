@extends('layouts.master')

@section('title', 'Ticket Verification Status')

@section('content')

<style>
    /* =========================================================
       TICKET VERIFICATION PAGE
       Responsive / Mobile First
       ========================================================= */

    .verification-page {
        min-height: calc(100vh - 80px);
        padding: 40px 15px 60px;
        background:
            radial-gradient(circle at 10% 10%, rgba(25, 135, 84, 0.08), transparent 30%),
            radial-gradient(circle at 90% 90%, rgba(13, 110, 253, 0.08), transparent 30%),
            #f5f7fb;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .verification-wrapper {
        width: 100%;
        max-width: 560px;
    }

    /* Header */

    .verification-header {
        text-align: center;
        margin-bottom: 22px;
    }

    .verification-header .small-label {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 13px;
        border-radius: 50px;
        background: #ffffff;
        border: 1px solid #e8ebf0;
        color: #6c757d;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
    }

    .verification-header h2 {
        margin: 13px 0 0;
        font-size: 1.55rem;
        font-weight: 800;
        color: #172033;
        letter-spacing: -0.02em;
    }

    /* Main Card */

    .ticket-card {
        position: relative;
        overflow: hidden;
        background: #ffffff;
        border-radius: 24px;
        box-shadow:
            0 20px 60px rgba(23, 32, 51, 0.10),
            0 3px 12px rgba(23, 32, 51, 0.04);
        border: 1px solid rgba(0, 0, 0, 0.04);
    }

    /* Top status section */

    .ticket-status {
        padding: 34px 25px 30px;
        text-align: center;
    }

    .status-icon {
        width: 76px;
        height: 76px;
        margin: 0 auto 16px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
    }

    .status-icon.active {
        color: #198754;
        background: rgba(25, 135, 84, 0.10);
        box-shadow:
            0 0 0 9px rgba(25, 135, 84, 0.045),
            0 8px 25px rgba(25, 135, 84, 0.10);
    }

    .status-icon.expired {
        color: #6c757d;
        background: rgba(108, 117, 125, 0.10);
        box-shadow:
            0 0 0 9px rgba(108, 117, 125, 0.045);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 15px;
        border-radius: 50px;
        font-size: 0.74rem;
        font-weight: 800;
        letter-spacing: 0.07em;
    }

    .status-badge.active {
        color: #146c43;
        background: #d1e7dd;
    }

    .status-badge.expired {
        color: #495057;
        background: #e9ecef;
    }

    .ticket-name {
        margin: 15px 0 5px;
        color: #172033;
        font-size: 1.7rem;
        font-weight: 800;
        line-height: 1.2;
        word-break: break-word;
    }

    .verification-text {
        color: #7b8494;
        font-size: 0.9rem;
        margin: 0;
    }

    /* Perforation */

    .ticket-divider {
        position: relative;
        height: 1px;
        border-top: 1px dashed #dfe3e8;
        margin: 0 22px;
    }

    .ticket-divider::before,
    .ticket-divider::after {
        content: "";
        position: absolute;
        top: -11px;
        width: 22px;
        height: 22px;
        background: #f5f7fb;
        border-radius: 50%;
    }

    .ticket-divider::before {
        left: -34px;
    }

    .ticket-divider::after {
        right: -34px;
    }

    /* Information */

    .ticket-info {
        padding: 28px 25px 25px;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 13px 0;
    }

    .info-item + .info-item {
        border-top: 1px solid #f0f2f5;
    }

    .info-icon {
        flex: 0 0 38px;
        width: 38px;
        height: 38px;
        border-radius: 11px;
        background: #f3f6fa;
        color: #495057;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .info-content {
        min-width: 0;
        flex: 1;
    }

    .info-label {
        display: block;
        margin-bottom: 3px;
        color: #8a93a2;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .info-value {
        display: block;
        color: #252b36;
        font-size: 0.95rem;
        font-weight: 700;
        line-height: 1.45;
        overflow-wrap: anywhere;
    }

    /* Message */

    .verification-message {
        margin: 0 25px 25px;
        padding: 17px 18px;
        border-radius: 15px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        font-size: 0.88rem;
        line-height: 1.55;
    }

    .verification-message.active {
        background: #eefaf4;
        color: #146c43;
        border: 1px solid #d5f0e1;
    }

    .verification-message.expired {
        background: #f3f4f5;
        color: #5f666d;
        border: 1px solid #e3e5e7;
    }

    .message-icon {
        flex: 0 0 auto;
        font-size: 1.05rem;
        margin-top: 2px;
    }

    /* Bottom */

    .verification-footer {
        text-align: center;
        margin-top: 18px;
        color: #9aa2ae;
        font-size: 0.72rem;
    }

    /* =========================================================
       MOBILE
       ========================================================= */

    @media (max-width: 576px) {

        .verification-page {
            min-height: calc(100vh - 60px);
            padding: 25px 12px 40px;
            align-items: flex-start;
        }

        .verification-wrapper {
            max-width: 100%;
        }

        .verification-header {
            margin-bottom: 16px;
        }

        .verification-header h2 {
            font-size: 1.25rem;
            margin-top: 10px;
        }

        .verification-header .small-label {
            font-size: 0.67rem;
            padding: 6px 11px;
        }

        .ticket-card {
            border-radius: 20px;
        }

        .ticket-status {
            padding: 28px 18px 25px;
        }

        .status-icon {
            width: 65px;
            height: 65px;
            font-size: 1.7rem;
            margin-bottom: 14px;
        }

        .status-badge {
            padding: 7px 12px;
            font-size: 0.68rem;
        }

        .ticket-name {
            font-size: 1.35rem;
            margin-top: 13px;
        }

        .verification-text {
            font-size: 0.82rem;
        }

        .ticket-info {
            padding: 20px 17px 18px;
        }

        .info-item {
            gap: 11px;
            padding: 12px 0;
        }

        .info-icon {
            flex-basis: 35px;
            width: 35px;
            height: 35px;
            border-radius: 9px;
            font-size: 0.9rem;
        }

        .info-label {
            font-size: 0.65rem;
        }

        .info-value {
            font-size: 0.88rem;
        }

        .verification-message {
            margin: 0 17px 18px;
            padding: 14px;
            font-size: 0.82rem;
            border-radius: 13px;
        }

        .verification-footer {
            font-size: 0.68rem;
            margin-top: 14px;
        }
    }

    @media (max-width: 360px) {

        .verification-page {
            padding-left: 8px;
            padding-right: 8px;
        }

        .ticket-name {
            font-size: 1.2rem;
        }

        .ticket-status {
            padding-left: 14px;
            padding-right: 14px;
        }

        .ticket-info {
            padding-left: 14px;
            padding-right: 14px;
        }

        .verification-message {
            margin-left: 14px;
            margin-right: 14px;
        }
    }
</style>

<div class="verification-page">


<div class="verification-wrapper">

    {{-- Page Header --}}
    <div class="verification-header">

        <div class="small-label">
            <i class="bi bi-shield-check"></i>
            Ticket Verification
        </div>

        <h2>Ticket Status</h2>

    </div>

    {{-- Ticket --}}
    <div class="ticket-card">

        {{-- Status --}}
        <div class="ticket-status">

            @if($isExpired)

                <div class="status-icon expired">
                    <i class="bi bi-clock-history"></i>
                </div>

                <div class="status-badge expired">
                    <i class="bi bi-x-circle-fill"></i>
                    STATUS: EXPIRED
                </div>

            @else

                <div class="status-icon active">
                    <i class="bi bi-check-circle-fill"></i>
                </div>

                <div class="status-badge active">
                    <i class="bi bi-check-circle-fill"></i>
                    STATUS: ACTIVE
                </div>

            @endif

            <h1 class="ticket-name">
                {{ $attendee->full_name }}
            </h1>

            <p class="verification-text">
                Ticket verification result
            </p>

        </div>

        {{-- Ticket Divider --}}
        <div class="ticket-divider"></div>

        {{-- Event Information --}}
        <div class="ticket-info">

            <div class="info-item">

                <div class="info-icon">
                    <i class="bi bi-calendar-event"></i>
                </div>

                <div class="info-content">
                    <span class="info-label">Event</span>
                    <span class="info-value">
                        {{ $attendee->ticketCategory->event->title }}
                    </span>
                </div>

            </div>

            <div class="info-item">

                <div class="info-icon">
                    <i class="bi bi-ticket-perforated"></i>
                </div>

                <div class="info-content">
                    <span class="info-label">Ticket Category</span>
                    <span class="info-value">
                        {{ $attendee->ticketCategory->name }}
                    </span>
                </div>

            </div>

            <div class="info-item">

                <div class="info-icon">
                    <i class="bi bi-calendar3"></i>
                </div>

                <div class="info-content">
                    <span class="info-label">Event Date</span>
                    <span class="info-value">
                        {{ \Carbon\Carbon::parse($attendee->ticketCategory->event->event_date)->format('F d, Y - h:i A') }}
                    </span>
                </div>

            </div>

        </div>

        {{-- Verification Message --}}
        @if($isExpired)

            <div class="verification-message expired">

                <div class="message-icon">
                    <i class="bi bi-info-circle-fill"></i>
                </div>

                <div>
                    <strong>Ticket Expired</strong><br>
                    This event has passed and the ticket status is now expired.
                </div>

            </div>

        @else

            <div class="verification-message active">

                <div class="message-icon">
                    <i class="bi bi-shield-check"></i>
                </div>

                <div>
                    <strong>Ticket Valid</strong><br>
                    This ticket is valid and the event is currently active.
                </div>

            </div>

        @endif

    </div>

    <div class="verification-footer">
        <i class="bi bi-shield-lock"></i>
        Secure ticket verification
    </div>

</div>


</div>

@endsection
