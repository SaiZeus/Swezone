@extends('layouts.master')

@section('title', 'Ticket Verification Status')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4 p-4 text-center">
                <div class="mb-3">
                    @if($isExpired)
                        <div class="badge bg-secondary p-2 fs-6">STATUS: EXPIRED</div>
                    @else
                        <div class="badge bg-success p-2 fs-6">STATUS: ACTIVE</div>
                    @endif
                </div>

                <h3 class="fw-bold mb-3">{{ $attendee->full_name }}</h3>
                
                <p class="text-muted mb-1"><strong>Event:</strong> {{ $attendee->ticketCategory->event->title }}</p>
                <p class="text-muted mb-1"><strong>Category:</strong> {{ $attendee->ticketCategory->name }}</p>
                <p class="text-muted mb-3"><strong>Event Date:</strong> {{ \Carbon\Carbon::parse($attendee->ticketCategory->event->event_date)->format('F d, Y - h:i A') }}</p>

                <div class="alert {{ $isExpired ? 'alert-secondary' : 'alert-success' }} mb-0">
                    @if($isExpired)
                        This event has passed and the ticket status is now expired.
                    @else
                        This ticket is valid and the event is currently active.
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection