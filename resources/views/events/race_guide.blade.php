@extends('layouts.master')

@section('title', 'Race Guide - ' . $event->title)

@section('content')

<style>
    .race-guide-section {
        position: relative;
        min-height: 100vh;
        background:
            radial-gradient(circle at 8% 5%, rgba(192, 132, 252, .18), transparent 28%),
            radial-gradient(circle at 92% 12%, rgba(216, 180, 254, .15), transparent 25%),
            linear-gradient(180deg, #faf5ff 0%, #f3e8ff 100%);
        color: #3b0764;
    }

    .race-guide-card {
        background: rgba(255, 255, 255, .95);
        border: 1px solid #e9d5ff;
        border-radius: 18px !important;
        box-shadow: 0 12px 35px rgba(147, 51, 234, .08);
        padding: 32px;
        backdrop-filter: blur(6px);
    }

    .race-guide-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .race-guide-title {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .race-guide-title i {
        font-size: 1.6rem;
        color: #a855f7;
        background: #f3e8ff;
        width: 48px;
        height: 48px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }

    .race-guide-title h3 {
        margin: 0;
        color: #581c87;
        font-size: clamp(1.4rem, 2.5vw, 1.8rem);
        font-weight: 800;
        letter-spacing: -.02em;
    }

    .race-guide-title p {
        color: #7e22ce !important;
        font-size: .88rem;
    }

    .guide-tabs .btn-tab {
        border: 1px solid #d8b4fe;
        background: #faf5ff;
        color: #6b21a8;
        font-weight: 750;
        padding: 8px 18px;
        border-radius: 10px;
        font-size: 0.85rem;
        transition: all .2s ease;
        box-shadow: 0 2px 8px rgba(147, 51, 234, .03);
    }

    .guide-tabs .btn-tab:hover:not(.active) {
        background: #f3e8ff;
        border-color: #a855f7;
        color: #3b0764;
    }

    .guide-tabs .btn-tab.active {
        background: linear-gradient(135deg, #c084fc 0%, #a855f7 100%);
        color: #fff;
        border-color: transparent;
        box-shadow: 0 4px 14px rgba(168, 85, 247, .25);
    }

    .pdf-viewer-wrap {
        width: 100%;
        height: 600px;
        border: 1px solid #e9d5ff;
        border-radius: 14px;
        overflow: hidden;
        background: #faf5ff;
        box-shadow: 0 4px 16px rgba(147, 51, 234, .03);
    }

    .pdf-viewer-wrap iframe {
        width: 100%;
        height: 100%;
        border: none;
        background: #faf5ff;
    }

    .accept-checkbox-card {
        background: #faf5ff;
        border: 1px solid #e9d5ff;
        border-radius: 12px;
        padding: 18px 20px;
        margin-top: 24px;
    }

    .accept-checkbox-card .form-check-input {
        border-color: #d8b4fe;
        cursor: pointer;
    }

    .accept-checkbox-card .form-check-input:checked {
        background-color: #a855f7;
        border-color: #a855f7;
    }

    .accept-checkbox-card label {
        color: #3b0764;
        font-size: .88rem;
        line-height: 1.4;
        cursor: pointer;
    }

    .accept-btn {
        min-height: 44px;
        background: linear-gradient(135deg, #c084fc 0%, #a855f7 100%);
        border: 0;
        color: #fff !important;
        font-size: .88rem;
        font-weight: 800;
        letter-spacing: .01em;
        padding: 10px 24px;
        border-radius: 10px;
        box-shadow: 0 6px 18px rgba(168, 85, 247, .3);
        transition: all .2s ease;
    }

    .accept-btn:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 8px 22px rgba(168, 85, 247, .4);
    }

    .accept-btn:disabled {
        background: #e9d5ff !important;
        color: #a855f7 !important;
        box-shadow: none !important;
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }

    @media (max-width: 767.98px) {
        .race-guide-section {
            padding-top: 40px !important;
            padding-bottom: 40px !important;
        }

        .race-guide-card {
            padding: 18px;
        }

        .pdf-viewer-wrap {
            height: 450px;
        }
    }
</style>

<section class="race-guide-section pt-60 pb-60">
    <div class="container" style="max-width: 960px;">
        <div class="race-guide-card">
            <div class="race-guide-header">
                <div class="race-guide-title">
                    <i class="fas fa-book-open"></i>
                    <div>
                        <h3>Official Event Race Guide</h3>
                        <p class="mb-0">{{ $event->title }}</p>
                    </div>
                </div>

                @if($event->english_race_guide && $event->burmese_race_guide)
                    <div class="guide-tabs d-flex gap-2">
                        <button type="button" class="btn-tab active" id="tab-en" onclick="switchGuide('en')">
                            <i class="fas fa-globe me-1"></i> English Guide
                        </button>
                        <button type="button" class="btn-tab" id="tab-mm" onclick="switchGuide('mm')">
                            <i class="fas fa-language me-1"></i> Burmese Guide
                        </button>
                    </div>
                @endif
            </div>

            <div class="pdf-viewer-wrap">
                @if($event->english_race_guide)
                    <iframe id="pdf-frame-en" src="{{ asset('storage/' . $event->english_race_guide) }}#toolbar=0&view=FitH"></iframe>
                @endif

                @if($event->burmese_race_guide)
                    <iframe id="pdf-frame-mm" src="{{ asset('storage/' . $event->burmese_race_guide) }}#toolbar=0&view=FitH" style="{{ $event->english_race_guide ? 'display: none;' : '' }}"></iframe>
                @endif
            </div>

            {{-- FORM DIRECTLY POSTS TO CHECKOUT PROCESS --}}
            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event->id }}">
                <input type="hidden" name="promo_code" value="{{ $promoCode }}">

                @foreach($attendees as $index => $attendee)
                    @foreach($attendee as $key => $value)
                        <input type="hidden" name="attendees[{{ $index }}][{{ $key }}]" value="{{ $value }}">
                    @endforeach
                @endforeach

                <div class="accept-checkbox-card d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="form-check m-0">
                        <input class="form-check-input" type="checkbox" id="check-read-guide" onchange="toggleSubmitBtn()">
                        <label class="form-check-label font-weight-bold" for="check-read-guide">
                            I have read, understood, and agree to follow all instructions in the Official Race Guide.
                        </label>
                    </div>

                    <button type="submit" class="btn accept-btn" id="btn-proceed" disabled>
                        Proceed to Payment <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

@push('scripts')
<script>
function switchGuide(lang) {
    const frameEn = document.getElementById('pdf-frame-en');
    const frameMm = document.getElementById('pdf-frame-mm');
    const tabEn = document.getElementById('tab-en');
    const tabMm = document.getElementById('tab-mm');

    if (lang === 'en') {
        if (frameEn) frameEn.style.display = 'block';
        if (frameMm) frameMm.style.display = 'none';
        if (tabEn) tabEn.classList.add('active');
        if (tabMm) tabMm.classList.remove('active');
    } else {
        if (frameEn) frameEn.style.display = 'none';
        if (frameMm) frameMm.style.display = 'block';
        if (tabEn) tabEn.classList.remove('active');
        if (tabMm) tabMm.classList.add('active');
    }
}

function toggleSubmitBtn() {
    const checkbox = document.getElementById('check-read-guide');
    const btn = document.getElementById('btn-proceed');
    if (checkbox && btn) {
        btn.disabled = !checkbox.checked;
    }
}
</script>
@endpush

@endsection