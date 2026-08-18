@extends('layouts.master')

@section('title', $event->title)

@section('content')

<style>
    /* =========================================================
       Event Details - Modern UI
       ========================================================= */

    .event-details-section {
        background:
            radial-gradient(circle at 10% 0%, rgba(13, 110, 253, .07), transparent 28%),
            radial-gradient(circle at 90% 10%, rgba(25, 135, 84, .06), transparent 25%),
            #f7f9fc;
    }

    .event-details-section .container {
        max-width: 1180px;
    }

    .event-banner {
        position: relative;
        overflow: hidden;
        border-radius: 24px !important;
        background: #fff;
        box-shadow: 0 18px 50px rgba(20, 35, 60, .12);
    }

    .event-banner img {
        display: block;
        width: 100%;
        min-height: 320px;
        max-height: 520px;
        object-fit: cover;
        transition: transform .45s ease;
    }

    .event-banner:hover img {
        transform: scale(1.015);
    }

    .event-details-section h2 {
        margin-top: 28px;
        margin-bottom: 12px;
        color: #182230;
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 800;
        letter-spacing: -.03em;
    }

    .event-details-section h3 {
        color: #182230;
        font-size: 1.55rem;
        font-weight: 800;
        margin-bottom: 18px;
    }

    .event-details-section h4 {
        color: #182230;
        font-weight: 750;
    }

    .event-description {
        margin-top: 28px !important;
        padding: 28px 30px;
        background: #fff;
        border: 1px solid #e9edf3;
        border-radius: 18px;
        box-shadow: 0 10px 35px rgba(20, 35, 60, .055);
    }

    .event-description p {
        color: #687386;
        line-height: 1.8;
        margin-bottom: 0;
    }

    .ticket-panel,
    .attendee-panel,
    .live-board-panel {
        background: #fff;
        border: 1px solid #e8edf4;
        border-radius: 22px;
        padding: 28px;
        box-shadow: 0 12px 40px rgba(20, 35, 60, .06);
    }

    .ticket-table-wrap {
        border: 1px solid #e9edf3;
        border-radius: 16px;
        overflow: hidden;
    }

    .ticket-table {
        margin-bottom: 0;
    }

    .ticket-table thead th {
        background: #182230 !important;
        color: #fff;
        border: 0;
        padding: 16px 18px;
        font-size: .82rem;
        font-weight: 700;
        letter-spacing: .03em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .ticket-table tbody td {
        padding: 18px;
        border-color: #edf0f4;
        color: #4f5b6d;
        vertical-align: middle;
    }

    .attendee-card {
        position: relative;
        margin-bottom: 16px;
        padding: 24px !important;
        border: 1px solid #e6ebf2 !important;
        border-radius: 18px !important;
        background: linear-gradient(180deg, #fff 0%, #fbfcfe 100%);
        box-shadow: 0 8px 28px rgba(20, 35, 60, .05);
    }

    .attendee-card h5 {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        color: #182230;
        font-weight: 800;
    }

    .attendee-card h5::before {
        content: "✓";
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #eaf3ff;
        color: #0d6efd;
        font-size: .82rem;
        font-weight: 900;
    }

    .checkout-summary {
        margin-top: 24px !important;
        padding: 26px !important;
        border: 1px solid #e5eaf1 !important;
        border-radius: 18px !important;
        background: #fff;
        box-shadow: 0 10px 35px rgba(20, 35, 60, .06);
    }

    .promo-input {
        min-height: 48px !important;
    }

    .btn-apply-promo {
        min-height: 48px;
        font-weight: 700;
        padding: 0 20px;
    }

    .summary-line {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .summary-line.total-line {
        border-top: 2px dashed #e9edf3;
        padding-top: 12px;
        margin-top: 12px;
    }

    .live-board-panel {
        overflow: hidden;
    }

    .live-board-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 12px !important;
        border-bottom: 0;
    }

    .live-board-tabs .nav-link {
        border: 1px solid #e0e6ee;
        border-radius: 999px;
        padding: 10px 17px;
        color: #647083;
        background: #fff;
        font-weight: 700;
        transition: all .2s ease;
    }

    .live-board-tabs .nav-link.active {
        border-color: #0d6efd;
        color: #fff;
        background: #0d6efd;
        box-shadow: 0 7px 18px rgba(13, 110, 253, .18);
    }

    .live-board-content {
        margin-top: 18px;
        padding: 8px !important;
        border: 0 !important;
        border-radius: 16px;
        background: #f7f9fc;
    }

    .runner-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #edf4ff;
        color: #0d6efd;
        font-weight: 800;
        font-size: .8rem;
    }

    .empty-board {
        margin: 0;
        padding: 30px 20px;
        text-align: center;
        color: #7b8798 !important;
        background: #fff;
        border: 1px dashed #dbe2eb;
        border-radius: 14px;
    }
</style>

<section class="event-details-section pt-120 pb-120">
    <div class="container">
        <!-- Event Header & Image -->
        <div class="row">
            <div class="col-lg-12">
                <div class="event-banner mb-4">
                    <img src="{{ $event->image ? asset('storage/' . $event->image) : asset('assets/img/about/img06.jpg') }}" alt="{{ $event->title }}" class="img-fluid w-100 rounded">
                </div>
                <h2>{{ $event->title }}</h2>
                <p class="text-muted">
                    <i class="far fa-map-marker-alt"></i> {{ $event->location }} | 
                    <i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y - h:i A') }} |
                    <span class="event-status badge bg-{{ $event->status === 'live' ? 'danger' : ($event->status === 'upcoming' ? 'success' : 'secondary') }}">
                        {{ strtoupper($event->status) }}
                    </span>
                </p>
                <div class="event-description mt-4">
                    <h4>Description</h4>
                    <p>{{ $event->description }}</p>
                </div>
            </div>
        </div>

        @if($event->status !== 'past')
        <!-- Ticket Selection Form -->
        <div class="row mt-5">
            <div class="col-lg-12">
                <div class="ticket-panel">
                    <h3>Select Tickets</h3>
                    <div class="table-responsive ticket-table-wrap">
                        <table class="table table-bordered align-middle ticket-table">
                            <thead class="table-dark">
                                <tr>
                                    <th>Category</th>
                                    <th>Local Price</th>
                                    <th>Foreign Price</th>
                                    <th>Availability</th>
                                    <th style="width: 150px;">Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($event->ticketCategories as $category)
                                <tr>
                                    <td><strong>{{ $category->name }}</strong></td>
                                    <td>${{ number_format($category->local_price, 2) }}</td>
                                    <td>
                                        {{ $category->foreign_price ? '$' . number_format($category->foreign_price, 2) : 'N/A' }}
                                    </td>
                                    <td>
                                        @if($category->capacity === null)
                                            <span class="text-success">Unlimited</span>
                                        @else
                                            <span class="text-warning">{{ $category->capacity - $category->tickets_sold }} left</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <button class="btn btn-outline-secondary btn-minus" type="button" data-id="{{ $category->id }}">-</button>
                                            <input type="number" class="form-control text-center ticket-qty" id="qty-{{ $category->id }}" 
                                                   data-id="{{ $category->id }}" 
                                                   data-name="{{ $category->name }}" 
                                                   data-local-price="{{ $category->local_price }}" 
                                                   data-foreign-price="{{ $category->foreign_price ?? $category->local_price }}" 
                                                   value="0" min="0" readonly>
                                            <button class="btn btn-outline-secondary btn-plus" type="button" data-id="{{ $category->id }}">+</button>
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

        <!-- Dynamic Attendee Forms -->
        <div class="row mt-4" id="checkout-section" style="display: none;">
            <div class="col-lg-12">
                <div class="attendee-panel">
                    <form action="{{ route('checkout.process') }}" method="POST">
                        @csrf
                        <input type="hidden" name="event_id" id="event_id" value="{{ $event->id }}">

                        <h3 class="mb-4">Attendee Details</h3>
                        <div id="attendee-forms-container"></div>

                        <!-- Promo Code & Total Breakdown -->
                        <div class="card checkout-summary">
                            <div class="row align-items-center">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="promo_code" class="form-label">Promo Code (Optional)</label>
                                    <div class="input-group">
                                        <input type="text" id="promo_code_input" name="promo_code" class="form-control promo-input text-uppercase" placeholder="Enter code">
                                        <button class="btn btn-primary btn-apply-promo" type="button" id="btn-apply-promo">Apply Code</button>
                                    </div>
                                    <div id="promo-message" class="mt-2 text-sm"></div>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <div class="summary-line">
                                        <span class="text-muted">Subtotal:</span>
                                        <span>$<span id="subtotal-amount">0.00</span></span>
                                    </div>
                                    <div class="summary-line text-success" id="discount-row" style="display: none;">
                                        <span>Discount Applied:</span>
                                        <span>-$<span id="discount-amount">0.00</span></span>
                                    </div>
                                    <div class="summary-line total-line">
                                        <span class="h5 font-weight-bold">Total Amount:</span>
                                        <span class="h3 font-weight-bold text-dark">$<span id="grand-total">0.00</span></span>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-lg payment-btn w-100 mt-3">Proceed to MMQR Payment</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <!-- Live Board Section -->
        <div class="row mt-5">
            <div class="col-lg-12">
                <div class="live-board-panel">
                    <h3>Participant Live Board</h3>
                    <ul class="nav nav-tabs live-board-tabs" id="liveBoardTabs" role="tablist">
                        @foreach($event->ticketCategories as $index => $category)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $index === 0 ? 'active' : '' }}" id="cat-tab-{{ $category->id }}" data-bs-toggle="tab" data-bs-target="#cat-pane-{{ $category->id }}" type="button" role="tab">
                                    {{ $category->name }}
                                </button>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content border border-top-0 p-4 live-board-content" id="liveBoardTabsContent">
                        @foreach($event->ticketCategories as $index => $category)
                            <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="cat-pane-{{ $category->id }}" role="tabpanel">
                                @if(isset($liveBoard[$category->id]) && $liveBoard[$category->id]->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Runner Name</th>
                                                    <th>Nationality</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($liveBoard[$category->id] as $key => $runner)
                                                    <tr>
                                                        <td><span class="runner-number">{{ $key + 1 }}</span></td>
                                                        <td>{{ $runner->full_name }}</td>
                                                        <td>{{ $runner->nationality }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="empty-board">No confirmed runners in this category yet.</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Dynamic Form & Promo Script -->
<script>
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

    let activeDiscount = 0;
    let activeDiscountType = null; // 'fixed' or 'percentage'

    document.querySelectorAll('.btn-plus').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            const input = document.getElementById(`qty-${id}`);
            input.value = parseInt(input.value) + 1;
            renderForms();
        });
    });

    document.querySelectorAll('.btn-minus').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            const input = document.getElementById(`qty-${id}`);
            if (parseInt(input.value) > 0) {
                input.value = parseInt(input.value) - 1;
                renderForms();
            }
        });
    });

    // Delegate dynamic nationality changes to update totals
    container.addEventListener('change', function(e) {
        if (e.target && e.target.classList.contains('nationality-select')) {
            calculateTotals();
        }
    });

    // Promo Code Verification
    btnApplyPromo.addEventListener('click', function() {
        const code = promoInput.value.trim().toUpperCase();
        const eventId = document.getElementById('event_id').value;

        if (!code) {
            showPromoMsg('Please enter a promo code.', 'text-danger');
            resetDiscount();
            return;
        }

        btnApplyPromo.disabled = true;
        btnApplyPromo.textContent = 'Verifying...';

        fetch(`/api/check-promo?code=${encodeURIComponent(code)}&event_id=${eventId}`)
            .then(res => res.json())
            .then(data => {
                btnApplyPromo.disabled = false;
                btnApplyPromo.textContent = 'Apply Code';

                if (data.valid) {
                    activeDiscount = parseFloat(data.discount_value);
                    activeDiscountType = data.discount_type;
                    showPromoMsg(`Code applied! (${data.discount_type === 'percentage' ? activeDiscount + '%' : '$' + activeDiscount} off)`, 'text-success');
                } else {
                    resetDiscount();
                    showPromoMsg(data.message || 'Invalid or expired promo code.', 'text-danger');
                }
                calculateTotals();
            })
            .catch(() => {
                btnApplyPromo.disabled = false;
                btnApplyPromo.textContent = 'Apply Code';
                resetDiscount();
                showPromoMsg('Failed to apply code. Try again.', 'text-danger');
                calculateTotals();
            });
    });

    function showPromoMsg(msg, className) {
        promoMessage.textContent = msg;
        promoMessage.className = `mt-2 text-sm ${className}`;
    }

    function resetDiscount() {
        activeDiscount = 0;
        activeDiscountType = null;
    }

    function calculateTotals() {
        let subtotal = 0;
        const cards = container.querySelectorAll('.attendee-card');

        cards.forEach(card => {
            const select = card.querySelector('.nationality-select');
            const localPrice = parseFloat(select.getAttribute('data-local'));
            const foreignPrice = parseFloat(select.getAttribute('data-foreign'));
            
            subtotal += (select.value === 'Foreigner') ? foreignPrice : localPrice;
        });

        let discountDeduction = 0;
        if (activeDiscount > 0) {
            if (activeDiscountType === 'percentage') {
                discountDeduction = (subtotal * activeDiscount) / 100;
            } else {
                discountDeduction = activeDiscount;
            }
        }

        if (discountDeduction > subtotal) {
            discountDeduction = subtotal;
        }

        const grandTotal = Math.max(0, subtotal - discountDeduction);

        subtotalEl.textContent = subtotal.toFixed(2);
        grandTotalEl.textContent = grandTotal.toFixed(2);

        if (discountDeduction > 0) {
            discountEl.textContent = discountDeduction.toFixed(2);
            discountRow.style.display = 'flex';
        } else {
            discountRow.style.display = 'none';
        }
    }

    function renderForms() {
        container.innerHTML = '';
        let formIndex = 0;

        qtyInputs.forEach(input => {
            const qty = parseInt(input.value);
            const catId = input.getAttribute('data-id');
            const catName = input.getAttribute('data-name');
            const localPrice = parseFloat(input.getAttribute('data-local-price'));
            const foreignPrice = parseFloat(input.getAttribute('data-foreign-price'));

            for (let i = 0; i < qty; i++) {
                const card = document.createElement('div');
                card.className = 'card mb-3 p-3 attendee-card';
                card.innerHTML = `
                    <h5>Attendee #${formIndex + 1} - Category: ${catName}</h5>
                    <input type="hidden" name="attendees[${formIndex}][ticket_category_id]" value="${catId}">
                    <div class="row g-3 mt-1">
                        <div class="col-md-4">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="attendees[${formIndex}][full_name]" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="attendees[${formIndex}][email]" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="attendees[${formIndex}][phone]" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">NRC / Passport</label>
                            <input type="text" name="attendees[${formIndex}][nrc_passport]" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nationality</label>
                            <select name="attendees[${formIndex}][nationality]" class="form-select nationality-select" data-index="${formIndex}" data-local="${localPrice}" data-foreign="${foreignPrice}" required>
                                <option value="Myanmar">Local (Myanmar)</option>
                                <option value="Foreigner">Foreigner</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">T-Shirt Size</label>
                            <select name="attendees[${formIndex}][tshirt_size]" class="form-select" required>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                                <option value="2XL">2XL</option>
                            </select>
                        </div>
                    </div>
                `;
                container.appendChild(card);
                formIndex++;
            }
        });

        if (formIndex > 0) {
            checkoutSection.style.display = 'block';
            calculateTotals();
        } else {
            checkoutSection.style.display = 'none';
        }
    }
});
</script>
@endsection