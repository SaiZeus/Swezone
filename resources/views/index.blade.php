@extends('layouts.master')

@section('title', 'Marathon Ticketing - Home')

@section('content')
    <section class="hero hero-style--two pos-rel bg_img" data-background="{{ asset('assets/img/bg/hero_bg02.jpg') }}">
        <div class="container">
            <div class="xb-hero_content text-center">
                <h2 class="title wow fadeInUp" data-wow-delay="0ms" data-wow-duration="600ms">Marathon Ticket Registration</h2>
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
                    <img src="{{ asset('assets/img/shape/man-shape.png') }}" alt="">
                </div>
            </div>
            <div class="img img--3" data-speed="-3" data-parallax='{"y" : 90, "scale" : 0.3}'>
                <div class="wow zoomIn" data-wow-delay="ms">
                    <img src="{{ asset('assets/img/shape/man-shape02.png') }}" alt="">
                </div>
            </div>
            <div class="img img--4" data-speed="2" data-parallax='{"y" : 180, "x" : -90, "scale" : 0.3}'>
                <div class="wow zoomIn" data-wow-delay="ms">
                    <img src="{{ asset('assets/img/shape/cursor-shape.png') }}" alt="">
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
                            Our Sponsors
                            <img src="{{ asset('assets/img/icon/sub-icon.svg') }}" alt="icon-image">
                        </span>
                    </div>
                    <div class="countdown ul_li_center" data-countdown="2025-12-30 12:00"></div>
                </div>
                <div class="offer-item">
                    <div class="xb-inner ul_li">
                        <div class="xb-location"><img src="{{ asset('assets/img/icon/location-icon.svg') }}" alt=""></div>
                        <p class="xb-vanue">Venue: Yangon, Myanmar</p>
                    </div>
                    <div class="xb-inner ul_li">
                        <div class="xb-location"><img src="{{ asset('assets/img/icon/calendar-icon.svg') }}" alt=""></div>
                        <p class="xb-vanue">Date: 2025 – 2026 Season</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="dark-bg pt-125 pb-120" id="events-section">
        <div class="container">
            <div class="sec-title sec-title--two text-center mb-60">
                <span class="sub-title color-heading">
                    <img src="{{ asset('assets/img/icon/sub-icon.svg') }}" alt="icon-image">
                    Explore Marathons
                    <img src="{{ asset('assets/img/icon/sub-icon.svg') }}" alt="icon-image">
                </span>
                <h2 class="title color-heading">Marathon Events</h2>
            </div>

            <ul class="nav nav-tabs justify-content-center mb-5 border-0" id="eventTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active px-4 py-2 font-weight-bold" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab">
                        Upcoming Events
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-4 py-2 font-weight-bold" id="live-tab" data-bs-toggle="tab" data-bs-target="#live" type="button" role="tab">
                        Live Now 🔥
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-4 py-2 font-weight-bold" id="past-tab" data-bs-toggle="tab" data-bs-target="#past" type="button" role="tab">
                        Past Events
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="eventTabContent">
                
                <div class="tab-pane fade show active" id="upcoming" role="tabpanel">
                    <div class="row">
                        @forelse($upcomingEvents as $event)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card h-100 bg-secondary text-white border-0 rounded overflow-hidden shadow">
                                    <img src="{{ $event->image ? asset('storage/' . $event->image) : asset('assets/img/about/img06.jpg') }}" class="card-img-top" style="height: 220px; object-fit: cover;" alt="{{ $event->title }}">
                                    <div class="card-body d-flex flex-column">
                                        <span class="badge bg-success mb-2 align-self-start">UPCOMING</span>
                                        <h4 class="card-title text-white">{{ $event->title }}</h4>
                                        <p class="card-text text-light small mb-2">
                                            <i class="far fa-map-marker-alt text-warning"></i> {{ $event->location }}<br>
                                            <i class="far fa-calendar-alt text-warning"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                        </p>
                                        <p class="card-text text-muted flex-grow-1">{{ Str::limit($event->description, 90) }}</p>
                                        <a href="{{ route('events.show', $event->id) }}" class="thm-btn design-btn w-100 text-center mt-3">Register Now</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center text-muted py-5">
                                <h5>No upcoming events at the moment.</h5>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="tab-pane fade" id="live" role="tabpanel">
                    <div class="row">
                        @forelse($liveEvents as $event)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card h-100 bg-secondary text-white border-0 rounded overflow-hidden shadow">
                                    <img src="{{ $event->image ? asset('storage/' . $event->image) : asset('assets/img/about/img06.jpg') }}" class="card-img-top" style="height: 220px; object-fit: cover;" alt="{{ $event->title }}">
                                    <div class="card-body d-flex flex-column">
                                        <span class="badge bg-danger mb-2 align-self-start">LIVE NOW</span>
                                        <h4 class="card-title text-white">{{ $event->title }}</h4>
                                        <p class="card-text text-light small mb-2">
                                            <i class="far fa-map-marker-alt text-warning"></i> {{ $event->location }}<br>
                                            <i class="far fa-calendar-alt text-warning"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                        </p>
                                        <p class="card-text text-muted flex-grow-1">{{ Str::limit($event->description, 90) }}</p>
                                        <a href="{{ route('events.show', $event->id) }}" class="thm-btn design-btn w-100 text-center mt-3">View Live Board & Register</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center text-muted py-5">
                                <h5>No live events taking place right now.</h5>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="tab-pane fade" id="past" role="tabpanel">
                    <div class="row">
                        @forelse($pastEvents as $event)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card h-100 bg-secondary text-white border-0 rounded overflow-hidden shadow">
                                    <img src="{{ $event->image ? asset('storage/' . $event->image) : asset('assets/img/about/img06.jpg') }}" class="card-img-top" style="height: 220px; object-fit: cover;" alt="{{ $event->title }}">
                                    <div class="card-body d-flex flex-column">
                                        <span class="badge bg-secondary mb-2 align-self-start">PAST</span>
                                        <h4 class="card-title text-white">{{ $event->title }}</h4>
                                        <p class="card-text text-light small mb-2">
                                            <i class="far fa-map-marker-alt text-warning"></i> {{ $event->location }}<br>
                                            <i class="far fa-calendar-alt text-warning"></i> {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                        </p>
                                        <p class="card-text text-muted flex-grow-1">{{ Str::limit($event->description, 90) }}</p>
                                        <a href="{{ route('events.show', $event->id) }}" class="btn btn-outline-light w-100 text-center mt-3">View Finisher Board</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center text-muted py-5">
                                <h5>No past events archived yet.</h5>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>

    <section class="team pt-125 pb-125">
        <div class="container">
            <div class="dc-team-wrapper pos-rel">
                <div class="dec-team-top ul_li_between mb-55">
                    <div class="sec-title sec-title--two wow xb-animetion-left" data-wow-delay="0ms" data-wow-duration="700ms">
                        <span class="sub-title">
                            <img src="{{ asset('assets/img/icon/sub-icon.svg') }}" alt="icon-image">
                            Our Speakers
                            <img src="{{ asset('assets/img/icon/sub-icon.svg') }}" alt="icon-image">
                        </span>
                        <h2 class="title">Honorable Speakers</h2>
                    </div>
                    <div class="team-btn">
                        <a href="{{ url('/team') }}" class="thm-btn design-btn">
                            View More Speakers
                            <img src="{{ asset('assets/img/icon/right-arrow.svg') }}" alt="">
                        </a>
                    </div>
                </div>
                <div class="dc-team-slider swiper-container">
                    <div class="swiper-wrapper">
                        @for ($i = 1; $i <= 7; $i++)
                        <div class="swiper-slide">
                            <div class="dc-team-item">
                                <div class="xb-item--inner">
                                    <div class="xb-item--avatar">
                                        <img src="{{ asset('assets/img/team/image0' . $i . '.jpg') }}" alt="Image">
                                    </div>
                                    <div class="xb-item--author">
                                        <h3 class="xb-item--name"><a href="#!">Everett Calloway</a></h3>
                                        <p class="xb-item--desig">Senior Director @ Nova</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                    <div class="team-shape"></div>
                </div>
                <div class="swiper-pagination"></div>
                <div class="team-slide-btn">
                    <div class="swiper-button-next"><i class="fa-regular fa-arrow-right"></i></div>
                    <div class="swiper-button-prev"><i class="fa-regular fa-arrow-left"></i></div>
                </div>
            </div>
        </div>
    </section>
    <div class="dark-bg">
        <section class="funfact pt-130 pb-125">
            <div class="container">
                <div class="dc-funfact-wrap bg_img" data-background="{{ asset('assets/img/bg/funfact-bg.jpg') }}">
                    <div class="row mt-none-50">
                        <div class="col-lg-6 mt-50">
                            <div class="funfact-heading">
                                <div class="sec-title sec-title--two">
                                    <span class="sub-title wow fadeInUp" data-wow-delay="0ms" data-wow-duration="600ms">
                                        <img src="{{ asset('assets/img/icon/sub-icon.svg') }}" alt="icon-image">
                                        Achievement meetco
                                        <img src="{{ asset('assets/img/icon/sub-icon.svg') }}" alt="icon-image">
                                    </span>
                                    <h2 class="title wow fadeInUp" data-wow-delay="150ms" data-wow-duration="600ms">Facts That'll Make You Go meetco!</h2>
                                    <p class="content wow fadeInUp" data-wow-delay="300ms" data-wow-duration="600ms">From design breakthroughs to fun behind-the-scenes moments, these standout stats and surprises from the summit will wow you.</p>
                                </div>
                                <div class="hero-btn mt-55 wow fadeInUp" data-wow-delay="450ms" data-wow-duration="600ms">
                                    <a href="{{ url('/ticket') }}" class="thm-btn design-btn">
                                        Register Your Ticket
                                        <img src="{{ asset('assets/img/icon/right-arrow.svg') }}" alt="">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mt-50">
                            <div class="xb-funfact-image wow xb-animetion-right" data-wow-delay="600ms" data-wow-duration="600ms">
                                <img src="{{ asset('assets/img/funfact/img01.png') }}" alt="Image">
                            </div>
                        </div>
                    </div>
                    <div class="dc-funfact-inner mt-80">
                        <div class="dc-funfact-item">
                            <h3 class="xb-item--number xb-odm"><span class="xbo" data-count="80">00</span><span class="suffix"></span></h3>
                            <span class="xb-item--text">Speakers</span>
                        </div>
                        <div class="dc-funfact-item">
                            <h3 class="xb-item--number xb-odm">
                                <span class="xbo" data-count="4">00</span><span class="suffix">k+</span>
                            </h3>
                            <span class="xb-item--text">Attendees</span>
                        </div>
                        <div class="dc-funfact-item">
                            <h3 class="xb-item--number xb-odm">
                                <span class="xbo" data-count="50">00</span><span class="suffix"></span>
                            </h3>
                            <span class="xb-item--text">Sessions</span>
                        </div>
                        <div class="dc-funfact-item">
                            <h3 class="xb-item--number xb-odm">
                                <span class="xbo" data-count="100">00</span><span class="suffix">+</span>
                            </h3>
                            <span class="xb-item--text">Decision Makers</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="brand pb-125">
            <div class="dc-brand-wrap">
                <div class="sec-title sec-title--two text-center mb-40">
                    <span class="sub-title color-heading">
                        <img src="{{ asset('assets/img/icon/sub-icon.svg') }}" alt="icon-image">
                        Achievement meetco
                        <img src="{{ asset('assets/img/icon/sub-icon.svg') }}" alt="icon-image">
                    </span>
                </div>
                <div class="dc-brand-item marquee-left">
                    <ul class="dc-brand-inner ul_li list-unstyled">
                        @for ($i = 1; $i <= 7; $i++)
                        <li class="dc-brand-logo">
                            <a href="#!">
                                <img src="{{ asset('assets/img/brand/brand-logo0' . $i . '.png') }}" alt="">
                            </a>
                        </li>
                        @endfor
                    </ul>
                </div>
                <div class="dc-brand-item marquee-right">
                    <ul class="dc-brand-inner ul_li list-unstyled">
                        @for ($i = 8; $i <= 13; $i++)
                        <li class="dc-brand-logo">
                            <a href="#!">
                                <img src="{{ asset('assets/img/brand/brand-logo' . sprintf('%02d', $i) . '.png') }}" alt="">
                            </a>
                        </li>
                        @endfor
                    </ul>
                </div>
            </div>
        </section>
        </div>

    <section class="schedule pt-125 pb-130 bg_img" data-background="{{ asset('assets/img/bg/schedule-bg.jpg') }}">
        <div class="xb-schedule-wrapper">
            <div class="container">
                <div class="sec-title sec-title--two text-center mb-60">
                    <span class="sub-title wow fadeInUp" data-wow-delay="0ms" data-wow-duration="600ms">
                        <img src="{{ asset('assets/img/icon/sub-icon.svg') }}" alt="icon-image">
                        meetco design summit Schedule
                        <img src="{{ asset('assets/img/icon/sub-icon.svg') }}" alt="icon-image">
                    </span>
                    <h2 class="title wow fadeInUp" data-wow-delay="150ms" data-wow-duration="600ms">Schedule of design summit</h2>
                </div>
                <div class="xb-schedule-wrap pos-rel z-1">
                    <div class="xb-schedule-nav-wrap mb-30">
                        <ul class="xb-schedule-nav ul_li nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">
                                 <span>Day 01</span>
                                 September 14,2025
                                 <span class="arrow">
                                    <svg width="20" height="15" viewBox="0 0 20 15" fill="none">
                                        <path d="M9.99992 15L-5.96007e-07 1.58893e-07L20 0L9.99992 15Z" fill="#fff" />
                                    </svg>
                                 </span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#tab2" type="button" role="tab" aria-controls="tab2" aria-selected="false">
                                 <span>Day 02</span>
                                 September 15,2025
                                 <span class="arrow">
                                    <svg width="20" height="15" viewBox="0 0 20 15" fill="none">
                                        <path d="M9.99992 15L-5.96007e-07 1.58893e-07L20 0L9.99992 15Z" fill="#fff" />
                                    </svg>
                                 </span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab2" data-bs-toggle="tab" data-bs-target="#tab3" type="button" role="tab" aria-controls="tab3" aria-selected="false">
                                 <span>Day 03</span>
                                 September 16,2025
                                 <span class="arrow">
                                    <svg width="20" height="15" viewBox="0 0 20 15" fill="none">
                                        <path d="M9.99992 15L-5.96007e-07 1.58893e-07L20 0L9.99992 15Z" fill="#fff" />
                                 </svg>
                                 </span>
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="xb-schedule-content-wrap">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="table-responsive">
                                    <table class="table xb-schedule-content">
                                        <thead>
                                            <tr>
                                                <th><img src="{{ asset('assets/img/icon/calendar.svg') }}" alt="Icon"> TIME</th>
                                                <th><img src="{{ asset('assets/img/icon/task-bord.svg') }}" alt="Icon"> session</th>
                                                <th><img src="{{ asset('assets/img/icon/speaker.svg') }}" alt="Icon"> Speakers</th>
                                                <th><img src="{{ asset('assets/img/icon/location-icon04.svg') }}" alt="Icon"> Venue</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>08:00 am – 9:00 am</td>
                                                <td>The Future of Design</td>
                                                <td>
                                                    <span>
                                                        <img src="{{ asset('assets/img/team/speaker01.png') }}" alt="">
                                                        Ethan Scott (Creative Lead, Figma)
                                                    </span>
                                                </td>
                                                <td>Main Hall</td>
                                            </tr>
                                            <tr>
                                                <td>09:30 AM – 10:30 AM</td>
                                                <td>UX That Tells a Story</td>
                                                <td>
                                                    <span>
                                                        <img src="{{ asset('assets/img/team/speaker02.png') }}" alt="">
                                                        Omar Lee (Lead UX, Adobe)
                                                    </span>
                                                </td>
                                                <td>Room A</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="shedule-shape">
                        <img src="{{ asset('assets/img/shape/shedual-shape.png') }}" alt="">
                    </div>
                </div>
           </div>
       </div>
    </section>
    <section class="pricing pt-125 pb-115" data-bg-color="#fff">
        <div class="container">
            <div class="sec-title sec-title--two text-center mb-60">
                <span class="sub-title color-heading wow fadeInUp" data-wow-delay="0ms" data-wow-duration="600ms">
                    <img src="{{ asset('assets/img/icon/sub-icon.svg') }}" alt="icon-image">
                    Join with us
                    <img src="{{ asset('assets/img/icon/sub-icon.svg') }}" alt="icon-image">
                </span>
                <h2 class="title color-heading wow fadeInUp" data-wow-delay="150ms" data-wow-duration="600ms">Simple pricing</h2>
            </div>
            <div class="xb-pricing-table wow fadeInUp" data-wow-delay="300ms" data-wow-duration="600ms">
                <div class="row mt-none-30 justify-content-md-center">
                    <div class="col-lg-4 col-md-8 mt-30">
                        <div class="xb-pricing-item">
                            <div class="xb-item--holder">
                                <div class="xb-item--icon">
                                    <img src="{{ asset('assets/img/icon/pricing-icon01.svg') }}" alt="icon">
                                </div>
                                <span class="xb-item--title">Starter Pass</span>
                                <p class="xb-item--content">Perfect for students and early-stage designers</p>
                                <span class="xb-item--line"></span>
                                <h2 class="xb-item--dollar">$99</h2>
                            </div>
                            <ul class="xb-item--list list-unstyled">
                                <li>✅ Access to all keynote sessions</li>
                                <li>✅ Entry to networking lounge</li>
                                <li>✅ Design showcase access</li>
                                <li>❌ No workshop access</li>
                            </ul>
                            <div class="pricing-btn mt-55">
                                <a href="{{ url('/contact') }}" class="thm-btn design-btn">
                                    Get Starter Pass
                                    <img src="{{ asset('assets/img/icon/white-icon.svg') }}" alt="">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endsection