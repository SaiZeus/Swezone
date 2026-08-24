@extends('layouts.master')

@section('title', $event->title)

@section('content')

<style>
    .event-details-section {
        position: relative;
        min-height: 100vh;
        background:
            radial-gradient(circle at 8% 5%, rgba(192, 132, 252, .18), transparent 28%),
            radial-gradient(circle at 92% 12%, rgba(216, 180, 254, .15), transparent 25%),
            linear-gradient(180deg, #faf5ff 0%, #f3e8ff 100%);
        color: #3b0764;
    }

    .event-details-section .container {
        max-width: 1080px;
    }

    .event-banner {
        position: relative;
        overflow: hidden;
        border-radius: 18px !important;
        background: #fff;
        box-shadow: 0 12px 35px rgba(147, 51, 234, .08);
        border: 1px solid rgba(233, 213, 255, .8);
    }

    .event-banner img {
        display: block;
        width: 100%;
        min-height: 240px;
        max-height: 380px;
        object-fit: cover;
        transition: transform .5s ease;
    }

    .event-banner:hover img {
        transform: scale(1.015);
    }

    .event-details-section h2 {
        margin-top: 20px;
        margin-bottom: 8px;
        color: #581c87;
        font-size: clamp(1.6rem, 3vw, 2.3rem);
        line-height: 1.15;
        font-weight: 800;
        letter-spacing: -.02em;
    }

    .event-details-section h3 {
        color: #581c87;
        font-size: 1.15rem;
        line-height: 1.2;
        font-weight: 800;
        margin-bottom: 12px;
        letter-spacing: -.01em;
    }

    .event-details-section .text-muted {
        color: #7e22ce !important;
        font-size: .88rem;
        line-height: 1.6;
    }

    .event-details-section .badge {
        padding: .35rem .6rem;
        border-radius: 999px;
        font-size: .62rem;
        letter-spacing: .06em;
        font-weight: 800;
    }

    .event-description,
    .ticket-panel,
    .attendee-panel,
    .items-panel {
        background: rgba(255, 255, 255, .95);
        border: 1px solid #e9d5ff;
        border-radius: 16px;
        box-shadow: 0 8px 25px rgba(147, 51, 234, .05);
        backdrop-filter: blur(6px);
    }

    .event-description {
        margin-top: 18px !important;
        padding: 18px 20px;
    }

    .event-description h4 {
        color: #581c87;
        font-size: .98rem;
        font-weight: 800;
        margin-bottom: 6px;
    }

    .event-description p {
        color: #6b21a8;
        line-height: 1.6;
        font-size: .88rem;
        margin-bottom: 0;
    }

    .event-details-section .d-flex.flex-wrap {
        gap: 8px 10px !important;
    }

    .event-details-section .d-flex.flex-wrap > span {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border: 1px solid #e9d5ff;
        border-radius: 999px;
        background: #faf5ff;
        color: #6b21a8 !important;
        font-size: .8rem;
        box-shadow: 0 2px 8px rgba(147, 51, 234, .03);
    }

    .event-details-section .d-flex.flex-wrap i.text-primary,
    .event-details-section .d-flex.flex-wrap i.text-success {
        color: #a855f7 !important;
    }

    .items-panel,
    .ticket-panel,
    .attendee-panel {
        padding: 18px 20px;
    }

    .items-panel h3 i.text-primary {
        color: #a855f7 !important;
    }

    .event-item-card {
        height: 100%;
        border: 1px solid #e9d5ff;
        border-radius: 12px;
        padding: 10px;
        text-align: center;
        background: #fff;
        box-shadow: 0 4px 12px rgba(147, 51, 234, .03);
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .event-item-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(147, 51, 234, .08);
    }

    .event-item-card img,
    .event-item-placeholder {
        width: 100%;
        height: 100px;
        object-fit: cover;
        border-radius: 999px;
        margin-bottom: 8px;
    }

    .event-item-placeholder {
        background: #f3e8ff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #c084fc;
        font-size: 1.4rem;
    }

    .event-item-card h6 {
        font-size: .82rem;
        line-height: 1.3;
        color: #581c87 !important;
    }

    .ticket-header-box {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 14px;
        margin-bottom: 14px;
        border-radius: 12px;
        background: linear-gradient(135deg, #c084fc 0%, #a855f7 100%);
        box-shadow: 0 4px 14px rgba(168, 85, 247, .25);
        color: #ffffff;
    }

    .ticket-header-title {
        display: flex;
        align-items: center;
        gap: 9px;
        font-size: .98rem;
        font-weight: 800;
        letter-spacing: .01em;
        margin: 0;
    }

    .ticket-header-title i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: rgba(255, 255, 255, .25);
        font-size: .85rem;
    }

    .ticket-header-tag {
        font-size: .7rem;
        font-weight: 700;
        background: rgba(255, 255, 255, .25);
        padding: 3px 9px;
        border-radius: 999px;
        letter-spacing: .03em;
    }

    .ticket-table-wrap {
        border: 1px solid #e9d5ff;
        border-radius: 12px;
        overflow: hidden;
    }

    .ticket-table {
        margin-bottom: 0 !important;
    }

    .ticket-table thead th {
        background: #6b21a8 !important;
        color: #faf5ff;
        border: 0;
        padding: 10px 14px;
        font-size: .68rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .06em;
        white-space: nowrap;
    }

    .ticket-table tbody tr:hover {
        background: #faf5ff;
    }

    .ticket-table tbody td {
        padding: 10px 14px;
        border-color: #f3e8ff;
        color: #6b21a8;
        vertical-align: middle;
        font-size: .85rem;
    }

    .ticket-table tbody td strong {
        color: #3b0764;
        font-weight: 800;
    }

    .ticket-table .input-group {
        min-width: 105px;
        max-width: 120px;
        margin: 0 auto;
    }

    .ticket-table .btn-minus,
    .ticket-table .btn-plus {
        width: 30px;
        height: 30px;
        padding: 0;
        border-radius: 7px !important;
        border-color: #d8b4fe;
        background: #fff;
        color: #6b21a8;
        font-size: .82rem;
        font-weight: 800;
        transition: all .15s ease;
    }

    .ticket-table .btn-minus:hover,
    .ticket-table .btn-plus:hover {
        border-color: #a855f7;
        color: #fff;
        background: #a855f7;
    }

    .ticket-table .ticket-qty {
        height: 30px !important;
        min-height: 30px !important;
        max-height: 30px !important;
        line-height: 30px !important;
        font-size: .82rem;
        border-color: #d8b4fe;
        background: #faf5ff;
        font-weight: 800;
        color: #3b0764;
        padding: 0 !important;
    }

    .attendee-card {
        position: relative;
        margin-bottom: 14px;
        padding: 16px !important;
        border: 1px solid #e9d5ff !important;
        border-radius: 14px !important;
        background: #ffffff;
        box-shadow: 0 4px 16px rgba(147, 51, 234, .03);
    }

    .attendee-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, #c084fc, #a855f7);
        border-radius: 14px 0 0 14px;
    }

    .attendee-card h5 {
        display: flex;
        align-items: center;
        gap: 7px;
        color: #3b0764 !important;
        font-size: .92rem;
        font-weight: 800;
        margin-bottom: 14px !important;
    }

    .attendee-card h5::before {
        content: "✓";
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #f3e8ff;
        color: #a855f7;
        font-size: .72rem;
        font-weight: 900;
    }

    .attendee-card .form-label,
    .checkout-summary .form-label {
        color: #6b21a8;
        font-size: .74rem;
        font-weight: 750;
        margin-bottom: 4px;
    }

    .attendee-card .form-control:not(textarea),
    .attendee-card .form-select,
    .checkout-summary .form-control:not(textarea) {
        height: 38px !important;
        min-height: 38px !important;
        max-height: 38px !important;
        padding: 4px 10px !important;
        border: 1px solid #d8b4fe;
        border-radius: 8px;
        background: #fff;
        color: #3b0764;
        font-size: .82rem;
        line-height: 1.4 !important;
        box-shadow: 0 1px 3px rgba(147, 51, 234, .02);
    }

    .attendee-card textarea.form-control {
        height: 60px !important;
        min-height: 60px !important;
        max-height: 90px !important;
        padding: 8px 10px !important;
        line-height: 1.4 !important;
        resize: vertical;
        border: 1px solid #d8b4fe;
        color: #3b0764;
    }

    .attendee-card textarea.form-control[name*="[medical_details]"] {
        height: 50px !important;
        min-height: 50px !important;
        max-height: 80px !important;
    }

    .attendee-card .form-control:focus,
    .attendee-card .form-select:focus,
    .checkout-summary .form-control:focus {
        border-color: #a855f7;
        box-shadow: 0 0 0 3px rgba(168, 85, 247, .18);
        outline: none;
    }

    .attendee-card .form-select:not([multiple]),
    .checkout-summary .form-select:not([multiple]) {
        background-image: linear-gradient(45deg, transparent 50%, #a855f7 50%),
                          linear-gradient(135deg, #a855f7 50%, transparent 50%);
        background-position: calc(100% - 14px) 50%, calc(100% - 9px) 50%;
        background-size: 5px 5px, 5px 5px;
        background-repeat: no-repeat;
        padding-right: 28px !important;
        appearance: none;
        -webkit-appearance: none;
        cursor: pointer;
    }

    .attendee-section-title {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 4px 0 12px;
        padding: 8px 10px;
        border-radius: 10px;
        background: #faf5ff;
        border: 1px solid #e9d5ff;
        color: #581c87;
        font-size: .82rem;
        font-weight: 800;
    }

    .attendee-section-title i {
        width: 24px;
        height: 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        background: #f3e8ff;
        color: #a855f7;
        font-size: .75rem;
    }

    .attendee-section {
        margin-bottom: 12px;
    }

    .required-star {
        color: #a855f7;
        font-weight: 900;
    }

    .checkout-summary {
        margin-top: 16px !important;
        padding: 16px 18px !important;
        border: 1px solid #e9d5ff !important;
        border-radius: 14px !important;
        background: #ffffff;
        box-shadow: 0 8px 22px rgba(147, 51, 234, .04);
    }

    .promo-card-box {
        background: linear-gradient(135deg, #faf5ff 0%, #f3e8ff 100%);
        border: 1px dashed #d8b4fe;
        border-radius: 10px;
        padding: 12px;
    }

    .promo-title {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 6px;
        color: #581c87;
        font-size: .8rem;
        font-weight: 850;
    }

    .promo-title i {
        color: #a855f7;
        font-size: .85rem;
    }

    .promo-input-wrap {
        display: flex;
        align-items: center;
        background: #fff;
        border: 1px solid #d8b4fe;
        border-radius: 8px;
        overflow: hidden;
        height: 36px !important;
        transition: border-color .15s ease;
    }

    .promo-input-wrap:focus-within {
        border-color: #a855f7;
        box-shadow: 0 0 0 3px rgba(168, 85, 247, .18);
    }

    .promo-input {
        border: 0 !important;
        box-shadow: none !important;
        font-weight: 700;
        font-size: .8rem !important;
        letter-spacing: .05em;
        text-transform: uppercase;
        padding: 4px 10px !important;
        height: 36px !important;
        min-height: 36px !important;
        max-height: 36px !important;
        line-height: 36px !important;
        width: 100%;
        color: #3b0764 !important;
    }

    .btn-apply-promo {
        background: #a855f7 !important;
        border: 0 !important;
        color: #fff !important;
        font-size: .75rem !important;
        font-weight: 800 !important;
        padding: 0 14px !important;
        height: 36px !important;
        line-height: 36px !important;
        white-space: nowrap;
        transition: background .2s ease;
    }

    .btn-apply-promo:hover {
        background: #9333ea !important;
    }

    .summary-line {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 6px;
        color: #6b21a8;
        font-size: .82rem;
    }

    .summary-line.total-line {
        border-top: 1px dashed #d8b4fe;
        padding-top: 10px;
        margin-top: 10px;
    }

    .payment-btn {
        min-height: 42px;
        border: 0;
        border-radius: 10px;
        background: linear-gradient(135deg, #c084fc 0%, #a855f7 100%);
        box-shadow: 0 6px 18px rgba(168, 85, 247, .3);
        font-size: .88rem;
        font-weight: 800;
        letter-spacing: .01em;
        transition: transform .15s ease, box-shadow .15s ease;
        color: #fff !important;
    }

    .payment-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 22px rgba(168, 85, 247, .4);
    }

    .event-details-section .alert {
        border: 1px solid transparent;
        border-radius: 12px !important;
        box-shadow: 0 6px 18px rgba(147, 51, 234, .04);
    }

    @media (max-width: 767.98px) {
        .event-details-section {
            padding-top: 40px !important;
            padding-bottom: 40px !important;
        }

        .event-banner img {
            min-height: 180px;
        }

        .event-description,
        .ticket-panel,
        .attendee-panel,
        .items-panel {
            padding: 14px;
        }

        .attendee-card {
            padding: 12px !important;
        }

        .ticket-table thead th,
        .ticket-table tbody td {
            padding: 8px 10px;
        }

        .checkout-summary {
            padding: 14px !important;
        }
    }
</style>

<section class="event-details-section pt-60 pb-60">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="event-banner mb-3">
                    <img src="{{ $event->image ? asset('storage/' . $event->image) : asset('assets/img/about/img06.jpg') }}" alt="{{ $event->title }}" class="img-fluid w-100 rounded">
                </div>
                <h2>{{ $event->title }}</h2>
                <p class="text-muted">
                    <i class="far fa-map-marker-alt"></i> {{ $event->location }} | 
                    <i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y - h:i A') }} |
                    <span class="badge bg-{{ $event->status === 'live' ? 'danger' : ($event->status === 'upcoming' ? 'success' : 'secondary') }}">
                        {{ strtoupper($event->status) }}
                    </span>
                </p>

                <div class="d-flex flex-wrap gap-2 text-dark font-weight-bold my-2">
                    @if($event->creator_name)
                        <span><i class="far fa-user-circle text-primary me-1"></i> Organized by: {{ $event->creator_name }}</span>
                    @endif
                    
                    @if($event->creator_phone || $event->organizer_phone)
                        <span><i class="fas fa-phone-alt text-success me-1"></i> Contact: {{ $event->creator_phone ?? $event->organizer_phone }}</span>
                    @endif
                </div>

                <div class="event-description mt-3">
                    <h4>Description</h4>
                    <p>{{ $event->description }}</p>
                </div>
            </div>
        </div>

        @if($event->items && $event->items->count() > 0)
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="items-panel">
                    <h3><i class="fas fa-gift text-primary me-2"></i> What's Included with Your Event Entry</h3>
                    <div class="row g-3 mt-1">
                        @foreach($event->items as $item)
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="event-item-card">
                                    @if($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}">
                                    @else
                                        <div class="event-item-placeholder"><i class="fas fa-box"></i></div>
                                    @endif
                                    <h6 class="mb-0 font-weight-bold text-dark">{{ $item->title ?? 'Event Perk' }}</h6>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($event->status === 'live')

            @if($isEventSoldOut)
                <div class="row mt-4">
                    <div class="col-lg-12">
                        <div class="alert alert-danger p-3 text-center rounded-3 shadow-sm mb-0">
                            <h4 class="alert-heading font-weight-bold mb-1"><i class="fas fa-exclamation-circle"></i> Event Sold Out</h4>
                            <p class="mb-0 small">This event has reached its maximum overall capacity limit. Ticket registration is currently closed.</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="row mt-4">
                    <div class="col-lg-12">
                        <div class="ticket-panel">
                            <div class="ticket-header-box">
                                <div class="ticket-header-title">
                                    <i class="fas fa-ticket-alt"></i>
                                    <span>Select Your Tickets</span>
                                </div>
                                <span class="ticket-header-tag">Instant Booking</span>
                            </div>

                            <div class="table-responsive ticket-table-wrap">
                                <table class="table table-bordered align-middle ticket-table">
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th>Local Price</th>
                                            <th>Foreign Price</th>
                                            <th>Availability</th>
                                            <th style="width: 130px;" class="text-center">Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($event->ticketCategories as $category)
                                        @php
                                            $categoryAvailable = $category->capacity === null ? null : max(0, $category->capacity - $category->tickets_sold);
                                            $isCategorySoldOut = $category->capacity !== null && $categoryAvailable <= 0;
                                        @endphp
                                        <tr>
                                            <td><strong>{{ $category->name }}</strong></td>
                                            <td>${{ number_format($category->local_price, 2) }}</td>
                                            <td>{{ $category->foreign_price ? '$' . number_format($category->foreign_price, 2) : 'N/A' }}</td>
                                            <td>
                                                @if($category->capacity === null)
                                                    <span class="text-success fw-bold">Unlimited</span>
                                                @elseif($isCategorySoldOut)
                                                    <span class="badge bg-danger">Sold Out</span>
                                                @else
                                                    <span class="text-warning fw-bold">{{ $categoryAvailable }} left</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <button class="btn btn-minus" type="button" data-id="{{ $category->id }}" {{ $isCategorySoldOut ? 'disabled' : '' }}>-</button>
                                                    <input type="number" class="form-control text-center ticket-qty" id="qty-{{ $category->id }}" 
                                                           data-id="{{ $category->id }}" 
                                                           data-name="{{ $category->name }}" 
                                                           data-local-price="{{ $category->local_price }}" 
                                                           data-foreign-price="{{ $category->foreign_price ?? '' }}" 
                                                           data-max="{{ $categoryAvailable ?? 999 }}"
                                                           value="0" min="0" readonly {{ $isCategorySoldOut ? 'disabled' : '' }}>
                                                    <button class="btn btn-plus" type="button" data-id="{{ $category->id }}" {{ $isCategorySoldOut ? 'disabled' : '' }}>+</button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3" id="checkout-section" style="display: none;">
                    <div class="col-lg-12">
                        <div class="attendee-panel">
                            <form action="{{ route('events.waiver', $event->id) }}" method="POST" id="checkout-form">
                                @csrf
                                <input type="hidden" name="event_id" id="event_id" value="{{ $event->id }}">

                                <h3 class="mb-3">Participate Details</h3>
                                <div id="attendee-forms-container"></div>

                                <div class="card checkout-summary">
                                    <div class="row align-items-center g-3">
                                        <div class="col-md-6">
                                            <div class="promo-card-box">
                                                <label for="promo_code_input" class="promo-title">
                                                    <i class="fas fa-tags"></i>
                                                    <span>Have a Promo Code?</span>
                                                </label>
                                                <div class="promo-input-wrap">
                                                    <input type="text" id="promo_code_input" name="promo_code" class="form-control promo-input" placeholder="ENTER CODE">
                                                    <button class="btn btn-apply-promo" type="button" id="btn-apply-promo">Apply</button>
                                                </div>
                                                <div id="promo-message" class="mt-1" style="font-size: 0.75rem;"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-md-end">
                                            <div class="summary-line">
                                                <span class="text-muted">Subtotal:</span>
                                                <span class="fw-bold">$<span id="subtotal-amount">0.00</span></span>
                                            </div>
                                            <div class="summary-line text-success" id="discount-row" style="display: none;">
                                                <span>Discount Applied:</span>
                                                <span class="fw-bold">-$<span id="discount-amount">0.00</span></span>
                                            </div>
                                            <div class="summary-line total-line">
                                                <span class="h6 font-weight-bold mb-0">Total Amount:</span>
                                                <span class="h4 font-weight-bold text-dark mb-0">$<span id="grand-total">0.00</span></span>
                                            </div>
                                            
                                            <button type="submit" class="btn btn-primary payment-btn w-100 mt-2">
                                                Proceed <i class="fas fa-arrow-right ms-1"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

        @elseif($event->status === 'upcoming')
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="alert alert-info p-3 text-center rounded-3 shadow-sm mb-0">
                        <h4 class="alert-heading font-weight-bold mb-1"><i class="fas fa-clock me-1"></i> Registration Opening Soon</h4>
                        <p class="mb-0 small">Ticket registration for this upcoming event is not open yet. Please check back once the event is live!</p>
                    </div>
                </div>
            </div>
        @else
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="alert alert-secondary p-3 text-center rounded-3 shadow-sm mb-0">
                        <h4 class="alert-heading font-weight-bold mb-1"><i class="fas fa-flag-checkered me-1"></i> Event Concluded</h4>
                        <p class="mb-0 small">This event has already ended. Ticket registration is closed.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Script with Debug Logging -->
@push('scripts')
<script>
const districtOptions = {
    "1": ["ကပတ", "ကမတ", "ခပန", "ခလဖ", "ဆဒန", "ဆပရ", "ဆဘန", "တဆလ", "တနန", "ဒဖယ", "နမန", "ပတအ", "ပနဒ", "ပဝန", "ဖကန", "ဗမန", "မကတ", "မကန", "မခဘ", "မစန", "မညန", "မမန", "မလန", "ရှကန", "ရှဗယ", "လဂျန", "ဟပန", "အဂျယ", "၀မန"],
    "2": ["ဒမဆ", "ဖဆန", "ဖရဆ", "ဘလခ", "မစန", "ရတန", "ရသန", "လကန"],
    "3": ["ကကရ", "ကဆက", "ကဒတ", "ကဒန", "ကမမ", "စကလ", "ပကန", "ဖပန", "ဘဂလ", "ဘသဆ", "ဘအန", "မဝတ", "ရသန", "လဘန", "လသန", "ဝလမ", "သတက", "သတန"],
    "4": ["ကခန", "ကပလ", "ဆမန", "တဇန", "တတန", "ထတလ", "ပလဝ", "ဖလန", "မတန", "မတပ", "ရခဒ", "ရဇန", "ဟခန"],
    "5": ["ကနန", "ကဘလ", "ကမန", "ကလတ", "ကလထ", "ကလန", "ကလဝ", "ကသန", "ခတန", "ခပန", "ခဥတ", "ခဥန", "ငဇန", "စကန", "ဆလက", "တဆန", "တမန", "ထခန", "ဒပယ", "နယန", "ပလန", "ပလဘ", "ဖပန", "ဗမန", "ဘတလ", "မကန", "မမတ", "မမန", "မရန", "မလန", "ယမပ", "ရဘန", "ရဥန", "လရန", "လဟန", "ဝလန", "ဝသန", "ဟမလ", "အတန", "အရတ"],
    "6": ["ကစန", "ကရရ", "ကလအ", "ကသန", "ခမန", "တသရ", "ထဝန", "ပလတ", "ပလန", "ဘပန", "မတန", "မမန", "ရဖြန", "လလန", "သရခ"],
    "7": ["ကကန", "ကတခ", "ကပက", "ကဝန", "ဇကန", "ညလပ", "တငန", "ထတပ", "ဒဥန", "နတလ", "ပခတ", "ပခန", "ပတဆ", "ပတတ", "ပတန", "ပနက", "ပမန", "ဖမန", "မညန", "မလန", "ရကန", "ရတန", "ရတရှ", "လပတ", "ဝမန", "သကန", "သဆန", "သနပ", "သဝတ", "အတန", "အဖန"],
    "8": ["ကထန", "ကမန", "ခမန", "ဂဂန", "ငဖန", "စတရ", "စလန", "ဆပဝ", "ဆဖန", "ဆမန", "တတက", "ထလန", "နမန", "ပခက", "ပဖြန", "ပမန", "မကန", "မတန", "မထန", "မဘန", "မမန", "မလန", "မသန", "ရစက", "ရနခ", "သရန", "အလန"],
    "9": ["ကဆန", "ကပတ", "ခမစ", "ခအစ", "ငဇန", "ငသရ", "စကတ", "စကန", "ဇဗသ", "ဇယသ", "ညဥန", "တကတ", "တကန", "တတဥ", "တသန", "ဒခသ", "နထက", "ပကခ", "ပဗသ", "ပဘန", "ပမန", "ပသက", "ပဥလ", "မကန", "မခန", "မတရ", "မထလ", "မမန", "မလန", "မသန", "မဟမ", "ရမသ", "လဝန", "ဝတန", "သစန", "သပက", "အမစ", "အမရ", "ဥတသ"],
    "10": ["ကထန", "ကမရ", "ခဆန", "ခဇန", "ပမန", "ဘလန", "မဒန", "မလမ", "ရမန", "လမန", "သထန", "သဖြရ"],
    "11": ["ကတန", "ကတလ", "ကဖန", "ဂမန", "စတန", "တကန", "တပဝ", "ပဏတ", "ပတန", "ဗတထ", "ဘသတ", "မတန", "မပတ", "မပန", "မအတ", "မအန", "မဥန", "ရဗန", "ရသတ", "သတန", "အမန"],
    "12": ["ကကက", "ကခက", "ကတတ", "ကတန", "ကမတ", "ကမန", "ကမရ", "ခရန", "စခန", "ဆကခ", "ဆကန", "တကန", "တတထ", "တတန", "တမန", "ထတပ", "ဒဂဆ", "ဒဂတ", "ဒဂန", "ဒဂမ", "ဒဂရ", "ဒပန", "ဒလန", "ပဇတ", "ပဘတ", "ဗဟန", "မဂတ", "မဂဒ", "မဘန", "မရက", "ရကန", "ရပသ", "လကန", "လမတ", "လမန", "လသန", "လသယ", "သကတ", "သခန", "သဃက", "သလန", "အစန", "အလန", "ဥကတ", "ဥကန", "ဥကမ"],
    "13": ["ကခန", "ကတတ", "ကတန", "ကတလ", "ကမဆ", "ကမန", "ကရန", "ကလတ", "ကလဒ", "ကလန", "ကလဖ", "ကသန", "ကဟန", "ခမန", "ခရဟ", "ခလန", "ဆဆန", "ဆဖန", "ညရန", "တကန", "တခလ", "တမည", "တယန", "တလန", "နကန", "နခတ", "နခန", "နခဝ", "နဆန", "နတန", "နတယ", "နဖန", "နမတ", "နဝန", "ပခန", "ပဆန", "ပတယ", "ပပက", "ပယန", "ပလတ", "ပလန", "ပဝန", "ဖခန", "မကန", "မခန", "မငန", "မဆတ", "မဆန", "မတတ", "မတန", "မနန", "မပန", "မဖန", "မဗတ", "မဘန", "မမဆ", "မမတ", "မမန", "မယန", "မရတ", "မရန", "မလန", "မဟရ", "ယလန", "ရငန", "ရစန", "ရဖန", "လကတ", "လခတ", "လခန", "လရန", "လလန", "လဟန", "သနန", "သပန", "ဟတန", "ဟပတ", "ဟပန", "အခန", "အတန"],
    "14": ["ကကထ", "ကကန", "ကခန", "ကပန", "ကလန", "ငဆန", "ငပတ", "ငရက", "ငသခ", "ငသယ", "ဇလန", "ညတန", "ဒဒရ", "ဒနဖြ", "ပစလ", "ပတန", "ပသန", "ဖပန", "ဘကလ", "မမက", "မမန", "မအန", "မအပ", "ရကန", "ရသယ", "လပတ", "လမန", "ဝခမ", "သပန", "ဟကကျ", "ဟသတ", "အဂပ", "အမတ", "အမန"]
};

const countriesList = [
    "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda", "Argentina", "Armenia", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros", "Congo", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Fiji", "Finland", "France", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Honduras", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Ivory Coast", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, North", "Korea, South", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique", "Namibia", "Nauru", "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Qatar", "Romania", "Russian Federation", "Rwanda", "St Kitts & Nevis", "St Lucia", "Saint Vincent & the Grenadines", "Samoa", "San Marino", "Sao Tome & Principe", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Sudan", "Spain", "Sri Lanka", "Sudan", "Suriname", "Swaziland", "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Togo", "Tonga", "Trinidad & Tobago", "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe"
];

// PHP to JS Conversion via Js::from (fixes ParseError & unexpected token)
const enabledFields = {!! \Illuminate\Support\Js::from($event->enabled_fields ?? ['viber', 'father_name', 'blood_type', 'tshirt_size', 'has_medical_condition', 'itra', 'experience', 'address']) !!};
const enableBibNumber = {!! \Illuminate\Support\Js::from((bool) ($event->enable_bib_number ?? true)) !!};

document.addEventListener('DOMContentLoaded', function () {
    const qtyInputs = document.querySelectorAll('.ticket-qty');
    const container = document.getElementById('attendee-forms-container');
    const checkoutSection = document.getElementById('checkout-section');
    const subtotalEl = document.getElementById('subtotal-amount');
    const discountEl = document.getElementById('discount-amount');
    const grandTotalEl = document.getElementById('grand-total');
    const discountRow = document.getElementById('discount-row');
    const promoInput = document.getElementById('promo_code_input');
    const btnApplyPromo = document.getElementById('btn-apply-promo');
    const promoMessage = document.getElementById('promo-message');

    let activePromo = null;

    document.querySelectorAll('.btn-plus').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            const input = document.getElementById('qty-' + id);
            const max = parseInt(input.getAttribute('data-max') || 999);
            const currentVal = parseInt(input.value);

            if (currentVal < max) {
                input.value = currentVal + 1;
                renderForms();
            }
        });
    });

    document.querySelectorAll('.btn-minus').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            const input = document.getElementById('qty-' + id);
            if (parseInt(input.value) > 0) {
                input.value = parseInt(input.value) - 1;
                renderForms();
            }
        });
    });

    if (container) {
        container.addEventListener('change', function(e) {
            if (e.target && e.target.classList.contains('nationality-select')) {
                const index = e.target.getAttribute('data-index');
                toggleNationality(index, e.target.value);
                calculateTotals();
            }
            if (e.target && e.target.classList.contains('nrc-state-select')) {
                const index = e.target.getAttribute('data-index');
                updateDistricts(index, e.target.value);
            }
            if (e.target && e.target.classList.contains('medical-select')) {
                const index = e.target.getAttribute('data-index');
                toggleMedical(index, e.target.value);
            }
            if (e.target && e.target.classList.contains('itra-select')) {
                const index = e.target.getAttribute('data-index');
                toggleITRA(index, e.target.value);
            }
        });
    }

    if (btnApplyPromo) {
        btnApplyPromo.addEventListener('click', function() {
            const code = promoInput.value.trim().toUpperCase();
            const eventId = document.getElementById('event_id').value;

            if (!code) {
                showPromoMsg('Please enter a promo code.', 'text-danger');
                resetDiscount();
                calculateTotals();
                return;
            }

            btnApplyPromo.disabled = true;
            btnApplyPromo.textContent = '...';

            fetch('/api/check-promo?code=' + encodeURIComponent(code) + '&event_id=' + eventId)
                .then(res => res.json())
                .then(data => {
                    btnApplyPromo.disabled = false;
                    btnApplyPromo.textContent = 'Apply';

                    if (data.valid) {
                        activePromo = {
                            value: parseFloat(data.discount_value),
                            type: data.discount_type,
                            categoryId: data.ticket_category_id ? parseInt(data.ticket_category_id) : null
                        };
                    } else {
                        resetDiscount();
                        showPromoMsg(data.message || 'Invalid promo code.', 'text-danger');
                    }
                    calculateTotals();
                })
                .catch(() => {
                    btnApplyPromo.disabled = false;
                    btnApplyPromo.textContent = 'Apply';
                    resetDiscount();
                    showPromoMsg('Failed to apply code. Try again.', 'text-danger');
                    calculateTotals();
                });
        });
    }

    function showPromoMsg(msg, className) {
        if (promoMessage) {
            promoMessage.textContent = msg;
            promoMessage.className = 'mt-1 ' + className;
        }
    }

    function resetDiscount() {
        activePromo = null;
    }

    window.updateDistricts = function(index, stateVal) {
        const districtSelect = document.getElementById('nrc_district_' + index);
        if (!districtSelect) return;
        districtSelect.innerHTML = '<option value="">District</option>';
        if (districtOptions[stateVal]) {
            districtOptions[stateVal].forEach(dist => {
                let opt = document.createElement("option");
                opt.value = dist;
                opt.textContent = dist;
                districtSelect.appendChild(opt);
            });
        }
    };

    window.toggleNationality = function(index, value) {
        const nrcBox = document.getElementById('nrc-box-' + index);
        const passportBox = document.getElementById('passport-box-' + index);
        if (!nrcBox || !passportBox) return;

        const nrcInputs = nrcBox.querySelectorAll('select, input');
        const passportInputs = passportBox.querySelectorAll('select, input');

        if (value === 'Foreigner') {
            nrcBox.style.display = 'none';
            passportBox.style.display = 'flex';
            nrcInputs.forEach(i => i.removeAttribute('required'));
            passportInputs.forEach(i => i.setAttribute('required', 'required'));
        } else {
            nrcBox.style.display = 'flex';
            passportBox.style.display = 'none';
            passportInputs.forEach(i => i.removeAttribute('required'));
            nrcInputs.forEach(i => i.setAttribute('required', 'required'));
        }
    };

    window.toggleMedical = function(index, value) {
        const detailsBox = document.getElementById('medical-details-box-' + index);
        const textarea = detailsBox ? detailsBox.querySelector('textarea') : null;
        if (!detailsBox || !textarea) return;

        if (value === 'yes') {
            detailsBox.style.display = 'block';
            textarea.setAttribute('required', 'required');
        } else {
            detailsBox.style.display = 'none';
            textarea.removeAttribute('required');
            textarea.value = '';
        }
    };

    window.toggleITRA = function(index, value) {
        const detailsBox = document.getElementById('itra-details-box-' + index);
        const input = detailsBox ? detailsBox.querySelector('input') : null;
        if (!detailsBox || !input) return;

        if (value === 'yes') {
            detailsBox.style.display = 'block';
            input.setAttribute('required', 'required');
        } else {
            detailsBox.style.display = 'none';
            input.removeAttribute('required');
            input.value = '';
        }
    };

    function calculateTotals() {
        if (!container) return;
        
        let subtotal = 0;
        let totalDiscount = 0;
        let applicableTicketsCount = 0;
        const cards = container.querySelectorAll('.attendee-card');

        cards.forEach(card => {
            const select = card.querySelector('.nationality-select');
            const catIdInput = card.querySelector('input[name*="[ticket_category_id]"]');
            const catId = catIdInput ? parseInt(catIdInput.value) : null;
            const localPrice = parseFloat(select.getAttribute('data-local'));
            const foreignPriceAttr = select.getAttribute('data-foreign');
            const foreignPrice = foreignPriceAttr !== '' ? parseFloat(foreignPriceAttr) : localPrice;
            
            let itemPrice = (select.value === 'Foreigner') ? foreignPrice : localPrice;
            subtotal += itemPrice;

            if (activePromo) {
                if (activePromo.categoryId === null || activePromo.categoryId === catId) {
                    applicableTicketsCount++;
                    if (activePromo.type === 'percentage') {
                        totalDiscount += (itemPrice * activePromo.value) / 100;
                    } else {
                        totalDiscount += activePromo.value;
                    }
                }
            }
        });

        if (activePromo) {
            if (activePromo.categoryId !== null && applicableTicketsCount === 0) {
                showPromoMsg('This promo code is not applicable to your selected ticket category.', 'text-danger');
            } else {
                showPromoMsg('Code applied! (' + (activePromo.type === 'percentage' ? activePromo.value + '%' : '$' + activePromo.value) + ' off eligible tickets)', 'text-success');
            }
        }

        if (totalDiscount > subtotal) {
            totalDiscount = subtotal;
        }

        const grandTotal = Math.max(0, subtotal - totalDiscount);

        if (subtotalEl) subtotalEl.textContent = subtotal.toFixed(2);
        if (grandTotalEl) grandTotalEl.textContent = grandTotal.toFixed(2);

        if (discountEl && discountRow) {
            if (totalDiscount > 0) {
                discountEl.textContent = totalDiscount.toFixed(2);
                discountRow.style.display = 'flex';
            } else {
                discountRow.style.display = 'none';
            }
        }
    }

    function isFieldEnabled(fieldKey) {
        return Array.isArray(enabledFields) && enabledFields.includes(fieldKey);
    }

    function renderForms() {
        if (!container) return;
        
        container.innerHTML = '';
        let formIndex = 0;

        qtyInputs.forEach(input => {
            const qty = parseInt(input.value);
            const catId = input.getAttribute('data-id');
            const catName = input.getAttribute('data-name');
            const localPrice = parseFloat(input.getAttribute('data-local-price'));
            const rawForeignPrice = input.getAttribute('data-foreign-price');
            const isForeignAvailable = rawForeignPrice !== '' && rawForeignPrice !== null && rawForeignPrice !== 'N/A';

            for (let i = 0; i < qty; i++) {
                const card = document.createElement('div');
                card.className = 'card attendee-card';

                let fieldsHTML = `
                    <h5 class="fw-bold">Participate #${formIndex + 1} - Category: ${catName}</h5>
                    <input type="hidden" name="attendees[${formIndex}][ticket_category_id]" value="${catId}">
                    
                    <div class="row g-2">
                        <!-- 1. Contact Information -->
                        <div class="attendee-section">
                            <div class="attendee-section-title">
                                <i class="fas fa-address-book"></i>
                                <span>1. Contact Information</span>
                            </div>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label">Email Address <span class="required-star">*</span></label>
                                    <input type="email" name="attendees[${formIndex}][email]" class="form-control" placeholder="you@example.com" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number <span class="required-star">*</span></label>
                                    <input type="text" name="attendees[${formIndex}][phone]" class="form-control" placeholder="09xxxxxxxxx" required>
                                </div>
                                ${isFieldEnabled('viber') ? `
                                <div class="col-md-6">
                                    <label class="form-label">Viber Number</label>
                                    <input type="text" name="attendees[${formIndex}][viber]" class="form-control" placeholder="Viber number">
                                </div>
                                ` : ''}
                                <div class="col-md-6">
                                    <label class="form-label">Emergency Contact Number <span class="required-star">*</span></label>
                                    <input type="tel" name="attendees[${formIndex}][emergency_contact]" class="form-control" placeholder="09xxxxxxxxx" required>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Required Information -->
                        <div class="attendee-section">
                            <div class="attendee-section-title">
                                <i class="fas fa-user"></i>
                                <span>2. Required Information</span>
                            </div>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="form-label">Full Name <span class="required-star">*</span></label>
                                    <input type="text" name="attendees[${formIndex}][full_name]" class="form-control" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Nationality <span class="required-star">*</span></label>
                                    <select name="attendees[${formIndex}][nationality]" class="form-select nationality-select" data-index="${formIndex}" data-local="${localPrice}" data-foreign="${isForeignAvailable ? rawForeignPrice : ''}" required>
                                        <option value="Myanmar">Local (Myanmar)</option>
                                        <option value="Foreigner" ${!isForeignAvailable ? 'disabled' : ''}>
                                            ${isForeignAvailable ? 'Foreigner' : 'Foreigner (N/A)'}
                                        </option>
                                    </select>
                                </div>

                                <!-- NRC Inputs (Local) -->
                                <div class="col-md-8" id="nrc-box-${formIndex}">
                                    <label class="form-label">NRC Number</label>
                                    <div class="row g-1">
                                        <div class="col-3">
                                            <select name="attendees[${formIndex}][nrc_state]" class="form-select nrc-state-select" data-index="${formIndex}" required>
                                                <option value="">State</option>
                                                <option value="1">၁/</option><option value="2">၂/</option><option value="3">၃/</option>
                                                <option value="4">၄/</option><option value="5">၅/</option><option value="6">၆/</option>
                                                <option value="7">၇/</option><option value="8">၈/</option><option value="9">၉/</option>
                                                <option value="10">၁၀/</option><option value="11">၁၁/</option><option value="12">၁၂/</option>
                                                <option value="13">၁၃/</option><option value="14">၁၄/</option>
                                            </select>
                                        </div>
                                        <div class="col-3">
                                            <select name="attendees[${formIndex}][nrc_district]" id="nrc_district_${formIndex}" class="form-select" required>
                                                <option value="">District</option>
                                            </select>
                                        </div>
                                        <div class="col-3">
                                            <select name="attendees[${formIndex}][nrc_naing]" class="form-select" required>
                                                <option value="နိုင်">နိုင်</option>
                                                <option value="ဧည့်">ဧည့်</option>
                                                <option value="စ">စ</option>
                                                <option value="ပြု">ပြု</option>
                                                <option value="သ">သ</option>
                                                <option value="သီ">သီ</option>
                                            </select>
                                        </div>
                                        <div class="col-3">
                                            <input name="attendees[${formIndex}][nrc_number]" type="text" inputmode="numeric" placeholder="123456" maxlength="6" pattern="\\d{6}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Passport & Country Inputs (Foreigner) -->
                                <div class="col-md-8 row g-1" id="passport-box-${formIndex}" style="display: none;">
                                    <div class="col-md-6">
                                        <label class="form-label">Passport Number</label>
                                        <input type="text" name="attendees[${formIndex}][passport_number]" class="form-control" placeholder="Passport No.">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Country</label>
                                        <select name="attendees[${formIndex}][country]" class="form-select">
                                            <option value="">Select Country</option>
                                            ${countriesList.map(c => `<option value="${c}">${c}</option>`).join('')}
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Gender</label>
                                    <select name="attendees[${formIndex}][gender]" class="form-select" required>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="prefer_not_to_say">Prefer not to say</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" name="attendees[${formIndex}][date_of_birth]" class="form-control" required>
                                </div>

                                ${isFieldEnabled('father_name') ? `
                                <div class="col-md-3">
                                    <label class="form-label">Father Name (Optional)</label>
                                    <input type="text" name="attendees[${formIndex}][father_name]" class="form-control" placeholder="Father's full name">
                                </div>
                                ` : ''}

                                ${isFieldEnabled('address') ? `
                                <div class="col-md-6">
                                    <label class="form-label">Address</label>
                                    <input type="text" name="attendees[${formIndex}][address]" class="form-control" placeholder="Full address...">
                                </div>
                                ` : ''}
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="attendee-section">
                                <div class="attendee-section-title">
                                    <i class="fas fa-running"></i>
                                    <span>3. Participate Information</span>
                                </div>
                                <div class="row g-2">
                                    ${enableBibNumber ? `
                                    <div class="col-md-4">
                                        <label class="form-label">BIB Name (Max 10 chars) <span class="required-star">*</span></label>
                                        <input type="text" name="attendees[${formIndex}][bib_name]" maxlength="10" class="form-control" placeholder="Runner Name" required>
                                    </div>
                                    ` : ''}

                                    ${isFieldEnabled('blood_type') ? `
                                    <div class="col-md-4">
                                        <label class="form-label">Blood Type <span class="required-star">*</span></label>
                                        <select name="attendees[${formIndex}][blood_type]" class="form-select" required>
                                            <option value="">Select Blood Type</option>
                                            <option value="A+">A+</option><option value="A-">A-</option>
                                            <option value="B+">B+</option><option value="B-">B-</option>
                                            <option value="O+">O+</option><option value="O-">O-</option>
                                            <option value="AB+">AB+</option><option value="AB-">AB-</option>
                                        </select>
                                    </div>
                                    ` : ''}

                                    ${isFieldEnabled('tshirt_size') ? `
                                    <div class="col-md-4">
                                        <label class="form-label">T-Shirt Size <span class="required-star">*</span></label>
                                        <select name="attendees[${formIndex}][tshirt_size]" class="form-select" required>
                                            <option value="">Select Size</option>
                                            <option value="S">S</option>
                                            <option value="M">M</option>
                                            <option value="L">L</option>
                                            <option value="XL">XL</option>
                                            <option value="2XL">2XL</option>
                                        </select>
                                    </div>
                                    ` : ''}

                                    ${isFieldEnabled('experience') ? `
                                    <div class="col-md-6">
                                        <label class="form-label">Running / Event Experience</label>
                                        <textarea name="attendees[${formIndex}][experience]" class="form-control" placeholder="Previous marathons or race experience..."></textarea>
                                    </div>
                                    ` : ''}

                                    ${isFieldEnabled('has_medical_condition') ? `
                                    <div class="col-md-3">
                                        <label class="form-label">Existing Medical Conditions? <span class="required-star">*</span></label>
                                        <select name="attendees[${formIndex}][has_medical_condition]" class="form-select medical-select" data-index="${formIndex}" required>
                                            <option value="no">No</option>
                                            <option value="yes">Yes</option>
                                        </select>
                                    </div>
                                    ` : ''}

                                    ${isFieldEnabled('itra') ? `
                                    <div class="col-md-3">
                                        <label class="form-label">ITRA? <span class="required-star">*</span></label>
                                        <select name="attendees[${formIndex}][itra]" class="form-select itra-select" data-index="${formIndex}" required>
                                            <option value="no">No</option>
                                            <option value="yes">Yes</option>
                                        </select>
                                    </div>
                                    ` : ''}

                                    ${isFieldEnabled('has_medical_condition') ? `
                                    <div class="col-md-6" id="medical-details-box-${formIndex}" style="display: none;">
                                        <label class="form-label">Medical Condition Details <span class="required-star">*</span></label>
                                        <textarea name="attendees[${formIndex}][medical_details]" class="form-control" placeholder="Specify medical conditions or allergies..."></textarea>
                                    </div>
                                    ` : ''}

                                    ${isFieldEnabled('itra') ? `
                                    <div class="col-md-6" id="itra-details-box-${formIndex}" style="display: none;">
                                        <label class="form-label">ITRA Details <span class="required-star">*</span></label>
                                        <input type="text" name="attendees[${formIndex}][itra_details]" class="form-control" placeholder="Enter ITRA details">
                                    </div>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                card.innerHTML = fieldsHTML;
                container.appendChild(card);
                formIndex++;
            }
        });

        if (checkoutSection) {
            if (formIndex > 0) {
                checkoutSection.style.display = 'block';
                calculateTotals();
            } else {
                checkoutSection.style.display = 'none';
            }
        }
    }
});
</script>
@endpush
@endsection