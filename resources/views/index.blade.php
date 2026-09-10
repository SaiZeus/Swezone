@extends('layouts.master')

@section('title', 'Swezon Event Ticketing - Home')

@section('content')

<style>
    /* =========================================================
       LIGHT PURPLE THEME & CARD CENTERING CUSTOM STYLES
       ========================================================= */

    /* Section Background */
    .events-light-purple-bg {
        background: radial-gradient(circle at 10% 10%, rgba(216, 180, 254, 0.25), transparent 30%),
                    radial-gradient(circle at 90% 90%, rgba(192, 132, 252, 0.2), transparent 30%),
                    linear-gradient(180deg, #faf5ff 0%, #f3e8ff 100%);
        padding-top: 100px;
        padding-bottom: 100px;
    }

    /* Section Header Styles */
    .events-header-title {
        color: #581c87 !important;
        font-weight: 800;
    }

    /* Category Titles & Icons */
    .category-title {
        font-size: 1.5rem;
        font-weight: 800;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .category-title.live { color: #dc2626; }
    .category-title.upcoming { color: #7e22ce; }
    .category-title.past { color: #6b21a8; }

    /* Card Styling */
    .event-card {
        background: #ffffff !important;
        border: 1px solid #e9d5ff !important;
        border-radius: 16px !important;
        box-shadow: 0 10px 30px rgba(147, 51, 234, 0.06);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        overflow: hidden;
    }

    .event-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 35px rgba(147, 51, 234, 0.12);
    }

    .event-card.upcoming-card {
        border-color: #d8b4fe !important;
        background: #faf5ff !important;
    }

    .event-card.past-card {
        opacity: 0.82;
        background: #f3e8ff !important;
        border-color: #e9d5ff !important;
    }

    .event-card-img {
        height: 220px;
        object-fit: cover;
        width: 100%;
    }

    .event-card .card-title {
        color: #3b0764 !important;
        font-weight: 800;
        font-size: 1.15rem;
        line-height: 1.3;
        margin-top: 4px;
        margin-bottom: 8px;
    }

    .event-card .card-text-details {
        color: #6b21a8 !important;
        font-size: 0.85rem;
        line-height: 1.6;
    }

    .event-card .card-text-details i {
        color: #a855f7 !important;
    }

    .event-card .card-text-desc {
        color: #7e22ce !important;
        font-size: 0.85rem;
        line-height: 1.5;
    }

    /* Badges */
    .badge-live {
        background-color: #ef4444 !important;
        color: #ffffff !important;
        font-weight: 800;
        letter-spacing: 0.05em;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 0.68rem;
    }

    .badge-upcoming {
        background-color: #a855f7 !important;
        color: #ffffff !important;
        font-weight: 800;
        letter-spacing: 0.05em;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 0.68rem;
    }

    .badge-past {
        background-color: #6b21a8 !important;
        color: #ffffff !important;
        font-weight: 800;
        letter-spacing: 0.05em;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 0.68rem;
    }

    /* Buttons */
    .btn-view-event {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 10px 16px;
        border-radius: 10px;
        background: linear-gradient(135deg, #c084fc 0%, #a855f7 100%);
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.88rem;
        border: none;
        box-shadow: 0 4px 14px rgba(168, 85, 247, 0.25);
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-view-event:hover {
        background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%);
        box-shadow: 0 6px 18px rgba(168, 85, 247, 0.35);
        color: #ffffff !important;
    }

    .btn-view-event-past {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 10px 16px;
        border-radius: 10px;
        background: #ffffff;
        color: #6b21a8 !important;
        border: 1px solid #c084fc;
        font-weight: 700;
        font-size: 0.88rem;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-view-event-past:hover {
        background: #f3e8ff;
        color: #581c87 !important;
        border-color: #a855f7;
    }

    .empty-event-msg {
        color: #7e22ce;
        background: rgba(255, 255, 255, 0.6);
        border: 1px dashed #d8b4fe;
        border-radius: 12px;
        padding: 20px;
    }
</style>

    <section class="hero hero-style--two pos-rel bg_img" data-background="{{ asset('assets/img/bg/hero_bg02.jpg') }}">
        <div class="container">
            <div class="xb-hero_content text-center">
                <h2 class="title wow fadeInUp" data-wow-delay="0ms" data-wow-duration="600ms">Event Ticket Registration</h2>
                <div class="hero-btn mt-50 wow fadeInUp" data-wow-delay="150ms" data-wow-duration="600ms">
                    <a href="#events-section" class="thm-btn design-btn">
                        Browse Events
                        <img src="{{ asset('assets/img/icon/right-arrow.svg') }}" alt="">
                    </a>
                </div>
            </div>
        </div>
        <div class="hero-content-img">
            <div class="img img--1" data-speed="2" data-parallax='{"y" : 90, "scale" : 0.3}'>
                <div class="wow zoomIn" data-wow-delay="ms">
                    <img src="{{ asset('assets/img/shape/star-shape.png') }}" alt="">
                </div>
            </div>
            <div class="img img--2" data-speed="3" data-parallax='{"y" : 90, "scale" : 0.3}'>
                <div class="wow zoomIn" data-wow-delay="ms">
                    <img src="{{ asset('assets/img/shape/star-shape.png') }}" alt="">
                </div>
            </div>
            <div class="img img--3" data-speed="-3" data-parallax='{"y" : 90, "scale" : 0.3}'>
                <div class="wow zoomIn" data-wow-delay="ms">
                    <img src="{{ asset('assets/img/shape/star-shape.png') }}" alt="">
                </div>
            </div>
            <div class="img img--4" data-speed="2" data-parallax='{"y" : 180, "x" : -90, "scale" : 0.3}'>
                <div class="wow zoomIn" data-wow-delay="ms">
                    <img src="{{ asset('assets/img/shape/star-shape.png') }}" alt="">
                </div>
            </div>
        </div>
    </section>

    <section class="offer-section dark-bg">
        <div class="container">
            <div class="offer-wrap offer-wrapper ul_li_between">
                <div class="offer-item">
                    <div class="sec-title sec-title--two">
                        <span class="sub-title">
                            <img src="{{ asset('assets/img/icon/sub-icon.svg') }}" alt="icon-image">
                            Countdown
                            <img src="{{ asset('assets/img/icon/sub-icon.svg') }}" alt="icon-image">
                        </span>
                    </div>
                    <div class="countdown ul_li_center" data-countdown="2026/12/12 06:00:00"></div>
                </div>
                <div class="offer-item">
                    <div class="xb-inner ul_li">
                        <div class="xb-location"><img src="{{ asset('assets/img/icon/location-icon.svg') }}" alt=""></div>
                        <p class="xb-vanue">Venue: Ananda Temple, Bagan, Myanmar</p>
                    </div>
                    <div class="xb-inner ul_li">
                        <div class="xb-location"><img src="{{ asset('assets/img/icon/calendar-icon.svg') }}" alt=""></div>
                        <p class="xb-vanue">Date: 12/12/2026</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- LIGHT PURPLE EVENTS SECTION -->
    <div class="events-light-purple-bg" id="events-section">
        <div class="container">
            <div class="sec-title sec-title--two text-center mb-60">
                <span class="sub-title color-heading">
                    <img src="{{ asset('assets/img/icon/sub-icon.svg') }}" alt="icon-image">
                    Explore Events
                    <img src="{{ asset('assets/img/icon/sub-icon.svg') }}" alt="icon-image">
                </span>
                <h2 class="title events-header-title">Events</h2>
            </div>

            <!-- ROW 1: LIVE EVENTS -->
            <div class="mb-5">
                <h3 class="category-title live"><i class="fas fa-broadcast-tower text-danger"></i> Live Now 🔥</h3>
                <div class="row justify-content-center">
                    @forelse($liveEvents as $event)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 event-card">
                                <img src="{{ $event->image ? asset('storage/' . $event->image) : asset('assets/img/about/img06.jpg') }}" class="card-img-top event-card-img" alt="{{ $event->title }}">
                                <div class="card-body d-flex flex-column">
                                    <span class="badge badge-live mb-2 align-self-start">LIVE NOW</span>
                                    <h4 class="card-title">{{ $event->title }}</h4>
                                    <p class="card-text-details mb-2">
                                        <i class="far fa-map-marker-alt me-1"></i> {{ $event->location }}<br>
                                        <i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                        @if($event->creator_name)
                                            <br><i class="far fa-user me-1"></i> Organized by: {{ $event->creator_name }}
                                        @endif
                                    </p>
                                    <p class="card-text-desc flex-grow-1">{{ Str::limit($event->description, 90) }}</p>
                                    <a href="{{ route('events.show', $event) }}" class="btn-view-event mt-3">View Event</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-3">
                            <div class="empty-event-msg">No live events taking place right now.</div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- ROW 2: UPCOMING EVENTS -->
            <div class="mb-5">
                <h3 class="category-title upcoming"><i class="far fa-calendar-check"></i> Upcoming Events</h3>
                <div class="row justify-content-center">
                    @forelse($upcomingEvents as $event)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 event-card upcoming-card">
                                <img src="{{ $event->image ? asset('storage/' . $event->image) : asset('assets/img/about/img06.jpg') }}" class="card-img-top event-card-img" alt="{{ $event->title }}">
                                <div class="card-body d-flex flex-column">
                                    <span class="badge badge-upcoming mb-2 align-self-start">UPCOMING</span>
                                    <h4 class="card-title">{{ $event->title }}</h4>
                                    <p class="card-text-details mb-2">
                                        <i class="far fa-map-marker-alt me-1"></i> {{ $event->location }}<br>
                                        <i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                        @if($event->creator_name)
                                            <br><i class="far fa-user me-1"></i> Organized by: {{ $event->creator_name }}
                                        @endif
                                    </p>
                                    <p class="card-text-desc flex-grow-1">{{ Str::limit($event->description, 90) }}</p>
                                    <a href="{{ route('events.show', $event) }}" class="btn-view-event mt-3">View Event</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-3">
                            <div class="empty-event-msg">No upcoming events at the moment.</div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- ROW 3: PAST EVENTS -->
            <div>
                <h3 class="category-title past"><i class="fas fa-history"></i> Past Events</h3>
                <div class="row justify-content-center">
                    @forelse($pastEvents as $event)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 event-card past-card">
                                <img src="{{ $event->image ? asset('storage/' . $event->image) : asset('assets/img/about/img06.jpg') }}" class="card-img-top event-card-img" alt="{{ $event->title }}">
                                <div class="card-body d-flex flex-column">
                                    <span class="badge badge-past mb-2 align-self-start">PAST</span>
                                    <h4 class="card-title">{{ $event->title }}</h4>
                                    <p class="card-text-details mb-2">
                                        <i class="far fa-map-marker-alt me-1"></i> {{ $event->location }}<br>
                                        <i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                        @if($event->creator_name)
                                            <br><i class="far fa-user me-1"></i> Organized by: {{ $event->creator_name }}
                                        @endif
                                    </p>
                                    <p class="card-text-desc flex-grow-1">{{ Str::limit($event->description, 90) }}</p>
                                    <a href="{{ route('events.show', $event) }}" class="btn-view-event-past mt-3">View Event</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-3">
                            <div class="empty-event-msg">No past events archived yet.</div>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    

    
@endsection