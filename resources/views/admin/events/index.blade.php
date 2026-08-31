@extends('layouts.admin')

@section('title', 'Manage Events')
@section('page-title', 'Events Directory')

@section('content')

<style>
    /* =========================================================
       EVENTS DIRECTORY - MODERN ADMIN UI
       ========================================================= */

    .events-directory {
        --events-primary: #4f46e5;
        --events-primary-light: #eef2ff;
        --events-text: #172033;
        --events-muted: #7b8798;
        --events-border: #e6e9ef;
        --events-bg: #f7f8fc;
    }

    /* =========================================
       PAGE HEADER
       ========================================= */

    .events-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .events-page-heading {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .events-page-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: #eef2ff;
        color: #4f46e5;
        font-size: 1.15rem;
    }

    .events-page-heading h2 {
        margin: 0;
        color: var(--events-text);
        font-size: 1.25rem;
        font-weight: 850;
        letter-spacing: -0.025em;
    }

    .events-page-heading p {
        margin: 3px 0 0;
        color: var(--events-muted);
        font-size: 0.76rem;
    }

    /* =========================================
       CREATE BUTTON
       ========================================= */

    .create-event-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 16px !important;
        border-radius: 11px !important;
        background: linear-gradient(
            135deg,
            #4f46e5 0%,
            #6366f1 100%
        ) !important;
        color: #ffffff !important;
        font-size: 0.76rem !important;
        font-weight: 800 !important;
        box-shadow: 0 7px 18px rgba(79, 70, 229, 0.18);
        transition:
            transform 0.18s ease,
            box-shadow 0.18s ease,
            filter 0.18s ease;
    }

    .create-event-button:hover {
        transform: translateY(-2px);
        filter: brightness(1.03);
        box-shadow: 0 11px 25px rgba(79, 70, 229, 0.25);
    }

    /* =========================================
       SUCCESS MESSAGE
       ========================================= */

    .event-success-alert {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        padding: 13px 16px;
        border: 1px solid #bbf7d0;
        border-radius: 13px;
        background: #f0fdf4;
        color: #166534;
        font-size: 0.78rem;
        font-weight: 650;
        box-shadow: 0 4px 15px rgba(22, 101, 52, 0.04);
    }

    .event-success-icon {
        width: 32px;
        height: 32px;
        min-width: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: #dcfce7;
        color: #16a34a;
    }

    /* =========================================
       TABLE CARD
       ========================================= */

    .events-table-card {
        overflow: hidden;
        border: 1px solid var(--events-border);
        border-radius: 20px;
        background: #ffffff;
        box-shadow: 0 12px 40px rgba(25, 35, 55, 0.055);
    }

    .events-table-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 19px 22px;
        border-bottom: 1px solid var(--events-border);
        background: linear-gradient(
            135deg,
            #ffffff 0%,
            #fafbff 100%
        );
    }

    .events-table-title {
        display: flex;
        align-items: center;
        gap: 9px;
        color: #263143;
        font-size: 0.86rem;
        font-weight: 850;
    }

    .events-table-title i {
        color: #6366f1;
    }

    .events-count-label {
        padding: 5px 9px;
        border-radius: 999px;
        background: #eef2ff;
        color: #4f46e5;
        font-size: 0.65rem;
        font-weight: 800;
    }

    /* =========================================
       TABLE
       ========================================= */

    .events-table {
        width: 100%;
        min-width: 1200px;
        border-collapse: collapse;
    }

    .events-table thead {
        background: #f8f9fc;
    }

    .events-table thead tr {
        border-bottom: 1px solid var(--events-border);
    }

    .events-table th {
        padding: 14px 18px;
        color: #7b8494;
        font-size: 0.66rem;
        font-weight: 850;
        letter-spacing: 0.075em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .events-table td {
        padding: 17px 18px;
        border-bottom: 1px solid #edf0f4;
        color: #5f6b7c;
        font-size: 0.79rem;
        vertical-align: middle;
    }

    .events-table tbody tr {
        transition: background 0.18s ease;
    }

    .events-table tbody tr:hover {
        background: #fafbff;
    }

    .events-table tbody tr:last-child td {
        border-bottom: 0;
    }

    /* =========================================
       EVENT INFORMATION
       ========================================= */

    .event-info {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 250px;
    }

    .event-image-wrapper {
        position: relative;
        width: 54px;
        height: 54px;
        min-width: 54px;
    }

    .event-image {
        width: 54px;
        height: 54px;
        object-fit: cover;
        border-radius: 13px;
        border: 1px solid #e3e7ed;
        box-shadow: 0 4px 12px rgba(20, 30, 50, 0.08);
    }

    .event-image-wrapper::after {
        content: "";
        position: absolute;
        right: -2px;
        bottom: -2px;
        width: 11px;
        height: 11px;
        border: 2px solid #ffffff;
        border-radius: 50%;
        background: #22c55e;
    }

    .event-info-text {
        min-width: 0;
    }

    .event-title {
        margin: 0 0 4px;
        color: #1d2939;
        font-size: 0.82rem;
        font-weight: 800;
        line-height: 1.3;
    }

    .event-description {
        max-width: 230px;
        margin: 0;
        color: #929baa;
        font-size: 0.68rem;
        line-height: 1.45;
    }

    /* =========================================
       LOCATION & DATE
       ========================================= */

    .event-location {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        color: #344054;
        font-weight: 700;
        line-height: 1.35;
    }

    .event-location i {
        margin-top: 2px;
        color: #6366f1;
        font-size: 0.72rem;
    }

    .event-date {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-top: 6px;
        color: #929baa;
        font-size: 0.68rem;
        white-space: nowrap;
    }

    .event-date i {
        color: #9aa3b1;
    }

    /* =========================================
       CATEGORY BADGES
       ========================================= */

    .categories-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        min-width: 180px;
        max-width: 270px;
    }

    .category-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 6px 9px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #f8fafc;
        color: #4b5563;
        font-size: 0.64rem;
        font-weight: 750;
        white-space: nowrap;
    }

    .category-badge i {
        color: #6366f1;
        font-size: 0.58rem;
    }

    .no-categories {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #a0a7b3;
        font-size: 0.68rem;
        font-style: italic;
    }

    /* =========================================
       EVENT ITEMS
       ========================================= */

    .event-items-wrapper {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 7px;
        min-width: 250px;
        max-width: 360px;
    }

    .event-item {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 5px 8px 5px 5px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        background: #ffffff;
        box-shadow: 0 2px 8px rgba(20, 30, 50, 0.04);
        white-space: nowrap;
    }

    .event-item-image {
        width: 30px;
        height: 30px;
        object-fit: cover;
        border-radius: 7px;
        border: 1px solid #e5e7eb;
    }

    .event-item-no-image {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 7px;
        background: #eef2ff;
        color: #6366f1;
        font-size: 0.65rem;
    }

    .event-item-title {
        color: #344054;
        font-size: 0.65rem;
        font-weight: 750;
    }

    .event-items-count {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 9px;
        border-radius: 8px;
        background: #eef2ff;
        color: #4f46e5;
        font-size: 0.64rem;
        font-weight: 800;
    }

    .no-items {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #a0a7b3;
        font-size: 0.68rem;
        font-style: italic;
    }

    /* =========================================
       STATUS
       ========================================= */

    .event-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 11px;
        border-radius: 999px;
        font-size: 0.64rem;
        font-weight: 850;
        letter-spacing: 0.035em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .event-status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }

    .status-live {
        background: #fef2f2;
        color: #dc2626;
    }

    .status-live .event-status-dot {
        background: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.12);
    }

    .status-upcoming {
        background: #ecfdf5;
        color: #059669;
    }

    .status-upcoming .event-status-dot {
        background: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.12);
    }

    .status-past {
        background: #f1f5f9;
        color: #64748b;
    }

    .status-past .event-status-dot {
        background: #94a3b8;
    }

    /* =========================================
       ACTION BUTTONS
       ========================================= */

    .event-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 6px;
        min-width: 320px;
    }

    .event-action-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-height: 33px;
        padding: 7px 10px;
        border-radius: 9px;
        font-size: 0.64rem;
        font-weight: 800;
        transition:
            background 0.18s ease,
            color 0.18s ease,
            transform 0.18s ease;
    }

    .event-action-button:hover {
        transform: translateY(-1px);
    }

    .promo-button {
        background: #f3e8ff;
        color: #7e22ce;
    }

    .promo-button:hover {
        background: #e9d5ff;
        color: #6b21a8;
    }

    .attendees-button {
        background: #eff6ff;
        color: #2563eb;
    }

    .attendees-button:hover {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .edit-button {
        background: #fffbeb;
        color: #d97706;
    }

    .edit-button:hover {
        background: #fef3c7;
        color: #b45309;
    }

    .delete-button {
        background: #fef2f2;
        color: #dc2626;
    }

    .delete-button:hover {
        background: #fee2e2;
        color: #b91c1c;
    }

    /* =========================================
       EMPTY STATE
       ========================================= */

    .events-empty-state {
        padding: 65px 20px !important;
        text-align: center;
    }

    .events-empty-icon {
        width: 62px;
        height: 62px;
        margin: 0 auto 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 18px;
        background: #eef2ff;
        color: #6366f1;
        font-size: 1.3rem;
    }

    .events-empty-state h3 {
        margin: 0 0 5px;
        color: #344054;
        font-size: 0.92rem;
        font-weight: 800;
    }

    .events-empty-state p {
        margin: 0;
        color: #929baa;
        font-size: 0.74rem;
    }

    .events-empty-state a {
        color: #4f46e5;
        font-weight: 750;
        text-decoration: none;
    }

    .events-empty-state a:hover {
        text-decoration: underline;
    }

    /* =========================================
       MOBILE
       ========================================= */

    @media (max-width: 767px) {

        .events-page-header {
            align-items: stretch;
            flex-direction: column;
        }

        .create-event-button {
            width: 100%;
        }

        .events-table-top {
            padding: 16px;
        }

        .events-table th,
        .events-table td {
            padding: 13px 14px;
        }

        .event-info {
            min-width: 230px;
        }

        .event-actions {
            min-width: 220px;
        }
    }
</style>


<div class="events-directory">

    {{-- =========================================
         PAGE HEADER
         ========================================= --}}
    <div class="events-page-header">

        <div class="events-page-heading">

            <div class="events-page-icon">
                <i class="fa-solid fa-person-running"></i>
            </div>

            <div>
                <h2>All Marathon Events</h2>

                <p>
                    Manage your marathon events, ticket categories,
                    event items and attendees.
                </p>
            </div>

        </div>


        <a
            href="{{ route('admin.events.create') }}"
            class="create-event-button"
        >
            <i class="fa-solid fa-plus"></i>
            Create New Event
        </a>

    </div>


    {{-- =========================================
         SUCCESS MESSAGE
         ========================================= --}}
    @if(session('success'))

        <div class="event-success-alert">

            <div class="event-success-icon">
                <i class="fa-solid fa-check"></i>
            </div>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif


    {{-- =========================================
         EVENTS TABLE
         ========================================= --}}
    <div class="events-table-card">

        <div class="events-table-top">

            <div class="events-table-title">

                <i class="fa-solid fa-calendar-days"></i>

                Event Directory

            </div>

            <span class="events-count-label">

                {{ $events->count() }} Marathon Events

            </span>

        </div>


        <div class="overflow-x-auto">

            <table class="events-table">

                <thead>

                    <tr>

                        <th>
                            Event
                        </th>

                        <th>
                            Location & Date
                        </th>

                        <th>
                            Categories
                        </th>

                        <th>
                            Event Items
                        </th>

                        <th>
                            Status
                        </th>

                        <th class="text-right">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($events as $event)

                        <tr>

                            {{-- =========================================
                                 EVENT
                                 ========================================= --}}
                            <td>

                                <div class="event-info">

                                    <div class="event-image-wrapper">

                                        <img
                                            src="{{ $event->image
                                                ? asset('storage/' . $event->image)
                                                : asset('assets/img/about/img06.jpg') }}"
                                            alt="{{ $event->title }}"
                                            class="event-image"
                                        >

                                    </div>


                                    <div class="event-info-text">

                                        <p class="event-title">
                                            {{ $event->title }}
                                        </p>

                                        <p class="event-description">
                                            {{ Str::limit($event->description, 40) }}
                                        </p>

                                    </div>

                                </div>

                            </td>


                            {{-- =========================================
                                 LOCATION & DATE
                                 ========================================= --}}
                            <td>

                                <div class="event-location">

                                    <i class="fa-solid fa-location-dot"></i>

                                    <span>
                                        {{ $event->location }}
                                    </span>

                                </div>


                                <div class="event-date">

                                    <i class="fa-regular fa-calendar"></i>

                                    <span>
                                        {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y - h:i A') }}
                                    </span>

                                </div>

                            </td>


                            {{-- =========================================
                                 TICKET CATEGORIES
                                 ========================================= --}}
                            <td>

                                <div class="categories-wrapper">

                                    @if($event->ticketCategories->count() > 0)

                                        @foreach($event->ticketCategories as $category)

                                            <span class="category-badge">

                                                <i class="fa-solid fa-ticket"></i>

                                                {{ $category->name }}

                                                ({{ $category->tickets_sold }}/{{ $category->capacity ?? '∞' }})

                                            </span>

                                        @endforeach

                                    @else

                                        <span class="no-categories">

                                            <i class="fa-solid fa-circle-info"></i>

                                            No categories

                                        </span>

                                    @endif

                                </div>

                            </td>


                            {{-- =========================================
                                 EVENT ITEMS
                                 ========================================= --}}
                            <td>

                                <div class="event-items-wrapper">

                                    @if($event->items->count() > 0)

                                        @foreach($event->items->take(4) as $item)

                                            <div
                                                class="event-item"
                                                title="{{ $item->title }}"
                                            >

                                                @if($item->image)

                                                    <img
                                                        src="{{ asset('storage/' . $item->image) }}"
                                                        alt="{{ $item->title }}"
                                                        class="event-item-image"
                                                    >

                                                @else

                                                    <div class="event-item-no-image">

                                                        <i class="fa-solid fa-gift"></i>

                                                    </div>

                                                @endif


                                                <span class="event-item-title">

                                                    {{ Str::limit($item->title, 18) }}

                                                </span>

                                            </div>

                                        @endforeach


                                        @if($event->items->count() > 4)

                                            <span class="event-items-count">

                                                <i class="fa-solid fa-plus"></i>

                                                {{ $event->items->count() - 4 }} more

                                            </span>

                                        @endif

                                    @else

                                        <span class="no-items">

                                            <i class="fa-solid fa-circle-info"></i>

                                            No items

                                        </span>

                                    @endif

                                </div>

                            </td>


                            {{-- =========================================
                                 STATUS
                                 ========================================= --}}
                            <td>

                                @if($event->status === 'live')

                                    <span class="event-status status-live">

                                        <span class="event-status-dot"></span>

                                        Live Now

                                    </span>

                                @elseif($event->status === 'upcoming')

                                    <span class="event-status status-upcoming">

                                        <span class="event-status-dot"></span>

                                        Upcoming

                                    </span>

                                @else

                                    <span class="event-status status-past">

                                        <span class="event-status-dot"></span>

                                        Past

                                    </span>

                                @endif

                            </td>


                            {{-- =========================================
                                 ACTIONS
                                 ========================================= --}}
                            <td>

                                <div class="event-actions">

                                    {{-- Promo Codes --}}
                                    <a
                                        href="{{ route('admin.events.promo_codes', $event->id) }}"
                                        class="event-action-button promo-button"
                                    >

                                        <i class="fa-solid fa-tags"></i>

                                        Promo Codes

                                    </a>


                                    {{-- Attendees --}}
                                    <a
                                        href="{{ route('admin.events.attendees', $event->id) }}"
                                        class="event-action-button attendees-button"
                                    >

                                        <i class="fa-solid fa-users"></i>

                                        Attendees

                                    </a>


                                    {{-- Edit --}}
                                    <a
                                        href="{{ route('admin.events.edit', $event->id) }}"
                                        class="event-action-button edit-button"
                                    >

                                        <i class="fa-solid fa-pen-to-square"></i>

                                        Edit

                                    </a>


                                    {{-- Delete --}}
                                    <form
                                        action="{{ route('admin.events.destroy', $event->id) }}"
                                        method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Are you sure you want to delete this event?');"
                                    >

                                        @csrf

                                        @method('DELETE')


                                        <button
                                            type="submit"
                                            class="event-action-button delete-button"
                                        >

                                            <i class="fa-solid fa-trash"></i>

                                            Delete

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>


                    @empty

                        {{-- =========================================
                             EMPTY STATE
                             ========================================= --}}
                        <tr>

                            <td
                                colspan="6"
                                class="events-empty-state"
                            >

                                <div class="events-empty-icon">

                                    <i class="fa-solid fa-calendar-xmark"></i>

                                </div>


                                <h3>
                                    No Events Created Yet
                                </h3>


                                <p>

                                    Get started by

                                    <a href="{{ route('admin.events.create') }}">
                                        creating your first event
                                    </a>.

                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection