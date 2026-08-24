@extends('layouts.master')

@section('title', 'Order Confirmed!')

@section('content')

<style>
    .success-section {
        position: relative;
        min-height: 100vh;
        background:
            radial-gradient(circle at 8% 5%, rgba(192, 132, 252, .18), transparent 28%),
            radial-gradient(circle at 92% 12%, rgba(216, 180, 254, .15), transparent 25%),
            linear-gradient(180deg, #faf5ff 0%, #f3e8ff 100%);
        color: #3b0764;
    }

    .success-card {
        background: rgba(255, 255, 255, .95);
        border: 1px solid #e9d5ff;
        border-radius: 22px !important;
        padding: 48px 32px;
        box-shadow: 0 12px 35px rgba(147, 51, 234, .08);
        backdrop-filter: blur(6px);
    }

    .success-icon-wrap {
        width: 90px;
        height: 90px;
        margin: 0 auto 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f3e8ff;
        color: #a855f7;
        box-shadow: 0 6px 20px rgba(168, 85, 247, .18);
    }

    .success-icon-wrap i {
        font-size: 2.8rem;
    }

    .success-card h2 {
        color: #581c87;
        font-size: clamp(1.8rem, 3vw, 2.4rem);
        font-weight: 850;
        letter-spacing: -.02em;
    }

    .success-card .lead {
        color: #6b21a8;
        font-size: 1.05rem;
        font-weight: 700;
    }

    .success-card .text-muted {
        color: #7e22ce !important;
        font-size: .88rem;
        line-height: 1.6;
        max-width: 580px;
        margin: 0 auto;
    }

    .btn-home-outline {
        min-height: 46px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 28px;
        border: 1px solid #d8b4fe;
        border-radius: 12px;
        background: #fff;
        color: #6b21a8;
        font-size: .92rem;
        font-weight: 800;
        text-decoration: none;
        transition: all .15s ease;
        box-shadow: 0 4px 12px rgba(147, 51, 234, .04);
    }

    .btn-home-outline:hover {
        background: linear-gradient(135deg, #c084fc 0%, #a855f7 100%);
        border-color: transparent;
        color: #fff !important;
        transform: translateY(-1px);
        box-shadow: 0 8px 22px rgba(168, 85, 247, .35);
    }

    @media (max-width: 767.98px) {
        .success-section {
            padding-top: 40px !important;
            padding-bottom: 40px !important;
        }

        .success-card {
            padding: 28px 20px;
        }

        .success-icon-wrap {
            width: 75px;
            height: 75px;
        }

        .success-icon-wrap i {
            font-size: 2.2rem;
        }
    }
</style>

<section class="success-section pt-60 pb-60">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="success-card">
                    <div class="success-icon-wrap">
                        <i class="fas fa-check-circle"></i>
                    </div>

                    <h2 class="mb-2">Congratulations!</h2>
                    <p class="lead mb-3">Your payment for Order <strong>#{{ $order->order_number }}</strong> was successful.</p>
                    <p class="text-muted mb-4">Individual ticket PDFs have been sent to the email addresses provided for each runner. You are now listed on the Live Board!</p>
                    
                    <a href="{{ route('home') }}" class="btn btn-home-outline">
                        <i class="fas fa-home me-2"></i> Return to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection