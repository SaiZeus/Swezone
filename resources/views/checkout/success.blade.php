@extends('layouts.master')

@section('title', 'Order Confirmed!')

@section('content')
<section class="success-section pt-120 pb-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="card p-5 shadow-sm border-0">
                    <div class="mb-3">
                        <i class="far fa-check-circle text-success" style="font-size: 80px;"></i>
                    </div>
                    <h2>Congratulations!</h2>
                    <p class="lead">Your payment for Order <strong>#{{ $order->order_number }}</strong> was successful.</p>
                    <p class="text-muted">Individual ticket PDFs have been sent to the email addresses provided for each runner. You are now listed on the Live Board!</p>
                    
                    <a href="{{ route('home') }}" class="btn btn-dark mt-3">Return to Home</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection