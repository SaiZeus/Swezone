@extends('layouts.master')

@section('title', 'Event Waiver - ' . $event->title)

@section('content')

<style>
    .waiver-section {
        position: relative;
        min-height: 100vh;
        background:
            radial-gradient(circle at 8% 5%, rgba(192, 132, 252, .18), transparent 28%),
            radial-gradient(circle at 92% 12%, rgba(216, 180, 254, .15), transparent 25%),
            linear-gradient(180deg, #faf5ff 0%, #f3e8ff 100%);
        color: #3b0764;
    }

    .waiver-section .container {
        max-width: 800px;
    }

    .waiver-card {
        background: rgba(255, 255, 255, .95);
        border: 1px solid #e9d5ff;
        border-radius: 18px !important;
        padding: 32px;
        box-shadow: 0 12px 35px rgba(147, 51, 234, .08);
        backdrop-filter: blur(6px);
    }

    .waiver-card h2 {
        color: #581c87;
        font-size: clamp(1.6rem, 3vw, 2.3rem);
        line-height: 1.15;
        font-weight: 800;
        letter-spacing: -.02em;
    }

    .waiver-card .text-muted {
        color: #7e22ce !important;
        font-size: .88rem;
    }

    .pdf-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-weight: 750;
        font-size: .85rem;
        padding: 10px 18px;
        border-radius: 12px;
        border: 1px solid #d8b4fe;
        background: #faf5ff;
        color: #6b21a8;
        text-decoration: none;
        transition: all .2s ease;
        box-shadow: 0 2px 8px rgba(147, 51, 234, .03);
    }

    .pdf-btn:hover {
        background: #f3e8ff;
        border-color: #a855f7;
        color: #3b0764;
    }

    .rules-box {
        background: #faf5ff;
        border: 1px solid #e9d5ff;
        border-radius: 14px;
        padding: 18px;
        max-height: 220px;
        overflow-y: auto;
    }

    .rules-box h6 {
        color: #581c87;
        font-size: .92rem;
    }

    .waiver-checkbox-box {
        background: #faf5ff;
        border: 1px solid #e9d5ff;
        border-radius: 12px;
        padding: 14px 16px;
    }

    .waiver-checkbox-box .form-check-input {
        border-color: #d8b4fe;
        cursor: pointer;
    }

    .waiver-checkbox-box .form-check-input:checked {
        background-color: #a855f7;
        border-color: #a855f7;
    }

    .waiver-checkbox-box label {
        color: #3b0764;
        font-size: .85rem;
        line-height: 1.4;
        cursor: pointer;
    }

    .payment-btn {
        min-height: 44px;
        border: 0;
        border-radius: 10px;
        background: linear-gradient(135deg, #c084fc 0%, #a855f7 100%);
        box-shadow: 0 6px 18px rgba(168, 85, 247, .3);
        font-size: .88rem;
        font-weight: 800;
        letter-spacing: .01em;
        transition: all .2s ease;
        color: #fff !important;
    }

    .payment-btn:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 8px 22px rgba(168, 85, 247, .4);
    }

    .payment-btn:disabled {
        background: #e9d5ff !important;
        color: #a855f7 !important;
        box-shadow: none !important;
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }

    .btn-back-outline {
        min-height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #d8b4fe;
        border-radius: 10px;
        background: #fff;
        color: #6b21a8;
        font-size: .88rem;
        font-weight: 800;
        text-decoration: none;
        transition: all .15s ease;
    }

    .btn-back-outline:hover {
        background: #faf5ff;
        border-color: #a855f7;
        color: #3b0764;
    }

    .waiver-section .alert {
        border: 1px solid transparent;
        border-radius: 12px !important;
        box-shadow: 0 6px 18px rgba(147, 51, 234, .04);
    }

    @media (max-width: 767.98px) {
        .waiver-section {
            padding-top: 40px !important;
            padding-bottom: 40px !important;
        }

        .waiver-card {
            padding: 18px;
        }
    }
</style>

<section class="waiver-section pt-60 pb-60">
    <div class="container">
        <div class="waiver-card">
            
            @if ($errors->any())
                <div class="alert alert-danger mb-4 p-3 rounded-3">
                    <h6 class="font-weight-bold mb-2"><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following issues:</h6>
                    <ul class="mb-0 ps-3 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <h2 class="font-weight-bold mb-1">Participant Terms & Waiver Agreement</h2>
            <p class="text-muted mb-4">Event: <strong>{{ $event->title }}</strong></p>

            @if($event->english_waiver || $event->burmese_waiver)
                <div class="row g-3 mb-4">
                    @if($event->english_waiver)
                        <div class="{{ $event->burmese_waiver ? 'col-md-6' : 'col-md-12' }}">
                            <a href="{{ asset('storage/' . $event->english_waiver) }}" target="_blank" class="pdf-btn w-100">
                                <i class="far fa-file-pdf text-danger fa-lg"></i> Download English Waiver (PDF)
                            </a>
                        </div>
                    @endif

                    @if($event->burmese_waiver)
                        <div class="{{ $event->english_waiver ? 'col-md-6' : 'col-md-12' }}">
                            <a href="{{ asset('storage/' . $event->burmese_waiver) }}" target="_blank" class="pdf-btn w-100">
                                <i class="far fa-file-pdf text-danger fa-lg"></i> Download Burmese Waiver (PDF)
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            <div class="rules-box mb-4">
                <h6 class="font-weight-bold mb-2">Terms & Conditions Summary:</h6>
                <ol class="mb-0 ps-3 text-muted text-sm" style="line-height: 1.8;">
                    <li>Participants must comply with event guidelines and follow marshal instructions at all times.</li>
                    <li>Registrations are non-refundable and non-transferable under any circumstances.</li>
                    <li>By agreeing below, you release event organizers from liability regarding injury or property loss during participation.</li>
                </ol>
            </div>

            <form action="{{ route('events.waiver.accept', $event->id) }}" method="POST">
                @csrf
                <input type="hidden" name="promo_code" value="{{ $promoCode }}">

                @foreach($attendees as $index => $attendee)
                    <input type="hidden" name="attendees[{{ $index }}][ticket_category_id]" value="{{ $attendee['ticket_category_id'] ?? '' }}">
                    <input type="hidden" name="attendees[{{ $index }}][full_name]" value="{{ $attendee['full_name'] ?? '' }}">
                    <input type="hidden" name="attendees[{{ $index }}][father_name]" value="{{ $attendee['father_name'] ?? '' }}">
                    <input type="hidden" name="attendees[{{ $index }}][email]" value="{{ $attendee['email'] ?? '' }}">
                    <input type="hidden" name="attendees[{{ $index }}][phone]" value="{{ $attendee['phone'] ?? '' }}">
                    <input type="hidden" name="attendees[{{ $index }}][viber]" value="{{ $attendee['viber'] ?? '' }}">
                    <input type="hidden" name="attendees[{{ $index }}][emergency_contact]" value="{{ $attendee['emergency_contact'] ?? '' }}">
                    <input type="hidden" name="attendees[{{ $index }}][nrc_passport]" value="{{ $attendee['nrc_passport'] ?? '' }}">
                    <input type="hidden" name="attendees[{{ $index }}][nationality]" value="{{ $attendee['nationality'] ?? '' }}">
                    <input type="hidden" name="attendees[{{ $index }}][country]" value="{{ $attendee['country'] ?? '' }}">
                    <input type="hidden" name="attendees[{{ $index }}][gender]" value="{{ $attendee['gender'] ?? '' }}">
                    <input type="hidden" name="attendees[{{ $index }}][date_of_birth]" value="{{ $attendee['date_of_birth'] ?? '' }}">
                    <input type="hidden" name="attendees[{{ $index }}][bib_name]" value="{{ $attendee['bib_name'] ?? '' }}">
                    <input type="hidden" name="attendees[{{ $index }}][tshirt_size]" value="{{ $attendee['tshirt_size'] ?? '' }}">
                    <input type="hidden" name="attendees[{{ $index }}][blood_type]" value="{{ $attendee['blood_type'] ?? '' }}">
                    <input type="hidden" name="attendees[{{ $index }}][has_medical_condition]" value="{{ $attendee['has_medical_condition'] ?? '' }}">
                    <input type="hidden" name="attendees[{{ $index }}][medical_details]" value="{{ $attendee['medical_details'] ?? '' }}">
                    <input type="hidden" name="attendees[{{ $index }}][itra]" value="{{ $attendee['itra'] ?? '' }}">
                    <input type="hidden" name="attendees[{{ $index }}][itra_details]" value="{{ $attendee['itra_details'] ?? '' }}">
                    <input type="hidden" name="attendees[{{ $index }}][address]" value="{{ $attendee['address'] ?? '' }}">
                    <input type="hidden" name="attendees[{{ $index }}][experience]" value="{{ $attendee['experience'] ?? '' }}">
                @endforeach

                <div class="waiver-checkbox-box mb-4">
                    <div class="form-check m-0">
                        <input class="form-check-input" type="checkbox" name="agree_waiver" id="agree_waiver" required>
                        <label class="form-check-label font-weight-bold" for="agree_waiver">
                            I have read, understood, and agree to all event rules, safety terms, and waivers in English / Burmese.
                        </label>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-6">
                        <a href="{{ route('events.show', $event->id) }}" class="btn btn-back-outline w-100">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                    <div class="col-6">
                        <button type="submit" class="btn payment-btn w-100" id="btn-proceed" disabled>
                            Proceed <i class="fas fa-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const agreeCheckbox = document.getElementById('agree_waiver');
    const proceedBtn = document.getElementById('btn-proceed');

    if (agreeCheckbox && proceedBtn) {
        agreeCheckbox.addEventListener('change', function () {
            proceedBtn.disabled = !this.checked;
        });
    }
});
</script>
@endpush
@endsection