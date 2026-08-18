@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Overview & Analytics')

@section('content')

<style>
    /* =========================================================
       ADMIN DASHBOARD - MODERN UI
       Functionality unchanged.
       ========================================================= */

    .admin-dashboard {
        --dash-bg: #f6f8fc;
        --dash-card: #ffffff;
        --dash-border: #e7ebf2;
        --dash-text: #172033;
        --dash-muted: #778397;
        --dash-indigo: #4f46e5;
        --dash-emerald: #059669;
        --dash-amber: #d97706;
    }

    .admin-dashboard {
        min-height: calc(100vh - 100px);
        margin: -1rem;
        padding: 1.5rem;
        background:
            radial-gradient(
                circle at 0% 0%,
                rgba(79, 70, 229, 0.07),
                transparent 28%
            ),
            radial-gradient(
                circle at 100% 5%,
                rgba(5, 150, 105, 0.06),
                transparent 25%
            ),
            var(--dash-bg);
    }

    /* =========================================================
       STAT CARDS
       ========================================================= */

    .dashboard-stat-card {
        position: relative;
        overflow: hidden;
        min-height: 145px;
        padding: 25px !important;
        border: 1px solid var(--dash-border) !important;
        border-radius: 22px !important;
        background: rgba(255, 255, 255, 0.96);
        box-shadow: 0 10px 35px rgba(20, 30, 50, 0.055);
        transition:
            transform 0.22s ease,
            box-shadow 0.22s ease,
            border-color 0.22s ease;
    }

    .dashboard-stat-card::before {
        content: "";
        position: absolute;
        width: 150px;
        height: 150px;
        right: -65px;
        top: -65px;
        border-radius: 50%;
        background: rgba(79, 70, 229, 0.045);
        pointer-events: none;
    }

    .dashboard-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 45px rgba(20, 30, 50, 0.1);
    }

    .dashboard-stat-card.revenue-card:hover {
        border-color: rgba(5, 150, 105, 0.4) !important;
    }

    .dashboard-stat-card.ticket-card:hover {
        border-color: rgba(79, 70, 229, 0.4) !important;
    }

    .dashboard-stat-card.event-card:hover {
        border-color: rgba(217, 119, 6, 0.4) !important;
    }

    .dashboard-stat-icon {
        position: relative;
        z-index: 1;
        width: 60px;
        height: 60px;
        min-width: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 17px !important;
    }

    .dashboard-stat-content {
        position: relative;
        z-index: 1;
    }

    .dashboard-stat-label {
        margin: 0 0 6px;
        color: var(--dash-muted);
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.055em;
    }

    .dashboard-stat-hint {
        display: inline-block;
        margin-left: 3px;
        font-size: 0.64rem;
        font-weight: 700;
        text-transform: none;
        letter-spacing: 0;
    }

    .dashboard-stat-value {
        margin: 0;
        color: var(--dash-text);
        font-size: 1.9rem;
        line-height: 1.15;
        font-weight: 850;
        letter-spacing: -0.035em;
    }

    /* =========================================================
       MAIN SECTION
       ========================================================= */

    .dashboard-section-card {
        overflow: hidden;
        border: 1px solid var(--dash-border);
        border-radius: 22px;
        background: var(--dash-card);
        box-shadow: 0 12px 40px rgba(20, 30, 50, 0.055);
    }

    .dashboard-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 22px 25px;
        border-bottom: 1px solid var(--dash-border);
        background: linear-gradient(
            135deg,
            #ffffff 0%,
            #fafbff 100%
        );
    }

    .dashboard-section-title {
        display: flex;
        align-items: center;
        gap: 11px;
        margin: 0;
        color: var(--dash-text);
        font-size: 1.05rem;
        font-weight: 850;
    }

    .dashboard-section-title-icon {
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #eef2ff;
        color: var(--dash-indigo);
    }

    /* =========================================================
       MAIN TABLE
       ========================================================= */

    .dashboard-table-wrapper {
        overflow-x: auto;
    }

    .dashboard-main-table {
        width: 100%;
        border-collapse: collapse;
    }

    .dashboard-main-table thead {
        background: #f8f9fc;
    }

    .dashboard-main-table thead tr {
        border-bottom: 1px solid var(--dash-border);
    }

    .dashboard-main-table th {
        padding: 14px 19px;
        color: #7a8495;
        font-size: 0.68rem;
        font-weight: 850;
        letter-spacing: 0.075em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .dashboard-main-table td {
        padding: 16px 19px;
        border-bottom: 1px solid #edf0f4;
        color: #596579;
        font-size: 0.86rem;
        vertical-align: middle;
    }

    .dashboard-main-table tbody tr {
        transition: background 0.18s ease;
    }

    .dashboard-main-table tbody tr:hover {
        background: #f9fbff;
    }

    .dashboard-runner-name {
        color: #1b2638 !important;
        font-weight: 750 !important;
    }

    .dashboard-ticket-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 30px;
        padding: 0 10px;
        border-radius: 9px;
        background: #eef2ff;
        color: #4f46e5;
        font-weight: 850;
    }

    .dashboard-loyalty-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 7px 12px !important;
        border-radius: 999px !important;
        font-size: 0.68rem !important;
        font-weight: 800 !important;
        white-space: nowrap;
    }

    /* =========================================================
       EMPTY STATE
       ========================================================= */

    .dashboard-empty-state {
        padding: 45px 20px !important;
        text-align: center;
        color: #8993a4 !important;
    }

    .dashboard-empty-icon {
        width: 48px;
        height: 48px;
        margin: 0 auto 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f1f4f8;
        color: #9aa4b3;
    }

    /* =========================================================
       MODALS
       ========================================================= */

    .dashboard-modal {
        background: rgba(15, 23, 42, 0.62) !important;
        backdrop-filter: blur(6px);
    }

    .dashboard-modal-panel {
        overflow: hidden;
        max-height: calc(100vh - 40px);
        border: 1px solid rgba(255, 255, 255, 0.55);
        border-radius: 22px !important;
        background: #fff;
        box-shadow: 0 30px 90px rgba(0, 0, 0, 0.23) !important;
        animation: dashboardModalOpen 0.2s ease-out;
    }

    @keyframes dashboardModalOpen {
        from {
            opacity: 0;
            transform: translateY(12px) scale(0.985);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .dashboard-modal-header {
        padding: 21px 25px !important;
        background: linear-gradient(
            135deg,
            #ffffff 0%,
            #fafbff 100%
        );
        border-bottom: 1px solid var(--dash-border);
    }

    .dashboard-modal-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
        color: var(--dash-text);
        font-size: 1rem !important;
        font-weight: 850 !important;
    }

    .dashboard-modal-close {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 0;
        border-radius: 10px;
        background: transparent;
        color: #8993a4;
        font-size: 1.25rem;
        transition: all 0.18s ease;
    }

    .dashboard-modal-close:hover {
        background: #fef2f2;
        color: #ef4444;
    }

    .dashboard-modal-body {
        max-height: 55vh;
        overflow: auto;
        padding: 0 25px 20px;
    }

    /* =========================================================
       LIVE / PAST SECTIONS
       ========================================================= */

    .revenue-status-section,
    .ticket-status-section {
        margin-top: 22px;
        overflow: hidden;
        border: 1px solid var(--dash-border);
        border-radius: 16px;
        background: #fff;
    }

    .status-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 15px 17px;
        border-bottom: 1px solid var(--dash-border);
    }

    .status-section-header.live {
        background: linear-gradient(
            135deg,
            #f0fdf4,
            #f7fff9
        );
    }

    .status-section-header.past {
        background: linear-gradient(
            135deg,
            #f8fafc,
            #f9fafb
        );
    }

    .status-section-name {
        display: flex;
        align-items: center;
        gap: 9px;
        color: #263244;
        font-size: 0.78rem;
        font-weight: 850;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .status-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
    }

    .status-dot.live {
        background: #22c55e;
        box-shadow: 0 0 0 4px rgba(34, 197, 94, .12);
    }

    .status-dot.past {
        background: #64748b;
        box-shadow: 0 0 0 4px rgba(100, 116, 139, .1);
    }

    .status-total {
        padding: 6px 10px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 850;
    }

    .status-total.live {
        background: #dcfce7;
        color: #15803d;
    }

    .status-total.past {
        background: #e2e8f0;
        color: #475569;
    }

    /* =========================================================
       MODAL TABLE
       ========================================================= */

    .dashboard-modal-table {
        width: 100%;
        border-collapse: collapse;
    }

    .dashboard-modal-table thead th {
        padding: 12px 12px;
        background: #f8f9fc;
        color: #7a8495;
        border-bottom: 1px solid var(--dash-border);
        font-size: 0.63rem;
        font-weight: 850;
        letter-spacing: 0.065em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .dashboard-modal-table tbody td {
        padding: 13px 12px;
        color: #596579;
        border-bottom: 1px solid #edf0f4;
        font-size: 0.81rem;
        vertical-align: middle;
    }

    .dashboard-modal-table tbody tr:hover {
        background: #fafbfe;
    }

    .dashboard-revenue {
        color: #059669 !important;
        font-weight: 850 !important;
    }

    .dashboard-past-revenue {
        color: #475569 !important;
        font-weight: 850 !important;
    }

    .dashboard-ticket-number-small {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 28px;
        padding: 0 9px;
        border-radius: 8px;
        background: #eef2ff;
        color: #4f46e5;
        font-weight: 850;
    }

    .dashboard-past-ticket-number {
        background: #f1f5f9;
        color: #475569;
    }

    /* =========================================================
       NO DATA
       ========================================================= */

    .status-no-data {
        padding: 25px 15px !important;
        text-align: center;
        color: #98a2b3 !important;
        font-size: .72rem !important;
    }

    .status-no-data i {
        margin-right: 5px;
        color: #c1c8d2;
    }

    /* =========================================================
       MODAL FOOTER
       ========================================================= */

    .dashboard-modal-footer {
        padding: 18px 25px !important;
        background: #fafbfc;
        border-top: 1px solid var(--dash-border);
    }

    .dashboard-close-button {
        padding: 9px 17px;
        border: 0;
        border-radius: 10px;
        background: #edf0f4;
        color: #4b5565;
        font-size: 0.78rem;
        font-weight: 800;
        transition: all 0.18s ease;
    }

    .dashboard-close-button:hover {
        background: #e1e5eb;
        color: #1f2937;
    }

    /* =========================================================
       MOBILE
       ========================================================= */

    @media (max-width: 767px) {

        .admin-dashboard {
            margin: -0.75rem;
            padding: 0.75rem;
        }

        .dashboard-stat-card {
            min-height: 125px;
            padding: 20px !important;
        }

        .dashboard-stat-icon {
            width: 52px;
            height: 52px;
            min-width: 52px;
        }

        .dashboard-stat-value {
            font-size: 1.55rem;
        }

        .dashboard-section-header {
            padding: 18px;
        }

        .dashboard-main-table th,
        .dashboard-main-table td {
            padding: 12px 14px;
        }

        .dashboard-modal-panel {
            max-height: calc(100vh - 24px);
            border-radius: 18px !important;
        }

        .dashboard-modal-header,
        .dashboard-modal-footer {
            padding-left: 18px !important;
            padding-right: 18px !important;
        }

        .dashboard-modal-body {
            padding-left: 18px;
            padding-right: 18px;
        }
    }
</style>


<div class="admin-dashboard">

    {{-- =====================================================
         STATISTICS
         ===================================================== --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        {{-- TOTAL REVENUE --}}
        <div
            onclick="openModal('revenueModal')"
            class="dashboard-stat-card revenue-card flex items-center space-x-4 cursor-pointer"
        >

            <div class="dashboard-stat-icon bg-emerald-100 text-emerald-600">
                <i class="fa-solid fa-sack-dollar text-2xl"></i>
            </div>

            <div class="flex-1 dashboard-stat-content">

                <p class="dashboard-stat-label">

                    Total Revenue

                    <span class="dashboard-stat-hint text-emerald-600">
                        (Click for details)
                    </span>

                </p>

                <h3 class="dashboard-stat-value">
                    ${{ number_format($totalRevenue, 2) }}
                </h3>

            </div>

        </div>


        {{-- TOTAL TICKETS --}}
        <div
            onclick="openModal('ticketsModal')"
            class="dashboard-stat-card ticket-card flex items-center space-x-4 cursor-pointer"
        >

            <div class="dashboard-stat-icon bg-indigo-100 text-indigo-600">
                <i class="fa-solid fa-ticket text-2xl"></i>
            </div>

            <div class="flex-1 dashboard-stat-content">

                <p class="dashboard-stat-label">

                    Tickets Sold

                    <span class="dashboard-stat-hint text-indigo-600">
                        (Click for details)
                    </span>

                </p>

                <h3 class="dashboard-stat-value">
                    {{ $totalTicketsSold }}
                </h3>

            </div>

        </div>


        {{-- ACTIVE EVENTS --}}
        <div class="dashboard-stat-card event-card flex items-center space-x-4">

            <div class="dashboard-stat-icon bg-amber-100 text-amber-600">
                <i class="fa-solid fa-person-running text-2xl"></i>
            </div>

            <div class="dashboard-stat-content">

                <p class="dashboard-stat-label">
                    Active Events
                </p>

                <h3 class="dashboard-stat-value">
                    {{ $activeEventsCount }}
                </h3>

            </div>

        </div>

    </div>


    {{-- =====================================================
         LOYALTY LEADERBOARD
         ===================================================== --}}
    <div class="dashboard-section-card mb-8">

        <div class="dashboard-section-header">

            <h2 class="dashboard-section-title">

                <span class="dashboard-section-title-icon">
                    <i class="fa-solid fa-award"></i>
                </span>

                Frequent Buyer Loyalty Leaderboard

            </h2>

        </div>


        <div class="dashboard-table-wrapper">

            <table class="dashboard-main-table text-left">

                <thead>

                    <tr>

                        <th>Runner Name</th>
                        <th>Email Address</th>
                        <th>Phone</th>
                        <th>Tickets Bought</th>
                        <th>Loyalty Badge</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($loyaltyRunners as $runner)

                    <tr>

                        <td class="dashboard-runner-name">
                            {{ $runner->full_name }}
                        </td>

                        <td>
                            {{ $runner->email }}
                        </td>

                        <td>
                            {{ $runner->phone }}
                        </td>

                        <td>

                            <span class="dashboard-ticket-number">
                                {{ $runner->ticket_count }}
                            </span>

                        </td>

                        <td>

                            @if($runner->ticket_count >= 5)

                                <span class="dashboard-loyalty-badge bg-purple-100 text-purple-700">
                                    <i class="fa-solid fa-medal"></i>
                                    Marathon Veteran
                                </span>

                            @elseif($runner->ticket_count >= 2)

                                <span class="dashboard-loyalty-badge bg-blue-100 text-blue-700">
                                    <i class="fa-solid fa-star"></i>
                                    Regular Runner
                                </span>

                            @else

                                <span class="dashboard-loyalty-badge bg-gray-100 text-gray-700">
                                    <i class="fa-solid fa-user"></i>
                                    First-Time Runner
                                </span>

                            @endif

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="5" class="dashboard-empty-state">

                            <div class="dashboard-empty-icon">
                                <i class="fa-solid fa-users-slash"></i>
                            </div>

                            No paid orders registered yet.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- =====================================================
         REVENUE MODAL
         LIVE + PAST SEPARATED
         ===================================================== --}}
    <div
        id="revenueModal"
        class="dashboard-modal fixed inset-0 hidden items-center justify-center z-50 p-4"
    >

        <div class="dashboard-modal-panel max-w-3xl w-full">


            {{-- HEADER --}}
            <div class="dashboard-modal-header flex justify-between items-center">

                <h3 class="dashboard-modal-title">

                    <i class="fa-solid fa-chart-column text-emerald-600"></i>

                    Revenue Overview

                </h3>

                <button
                    onclick="closeModal('revenueModal')"
                    class="dashboard-modal-close"
                >
                    &times;
                </button>

            </div>


            {{-- BODY --}}
            <div class="dashboard-modal-body">


                {{-- =========================================
                     LIVE EVENTS
                     ========================================= --}}
                <div class="revenue-status-section">

                    <div class="status-section-header live">

                        <div class="status-section-name">

                            <span class="status-dot live"></span>

                            Live Events

                        </div>

                        <span class="status-total live">

                            ${{ number_format(
                                collect($eventRevenueBreakdown)
                                    ->where('status', 'live')
                                    ->sum('real_revenue'),
                                2
                            ) }}

                        </span>

                    </div>


                    <div class="overflow-x-auto">

                        <table class="dashboard-modal-table">

                            <thead>

                                <tr>

                                    <th>
                                        Event
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th class="text-right">
                                        Revenue
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @php
                                    $liveRevenue = collect($eventRevenueBreakdown)
                                        ->where('status', 'live');
                                @endphp


                                @forelse($liveRevenue as $item)

                                <tr>

                                    <td class="font-semibold text-gray-800">
                                        {{ $item->title }}
                                    </td>

                                    <td>

                                        <span class="dashboard-status bg-green-100 text-green-700">
                                            <i class="fa-solid fa-circle text-[6px] mr-1"></i>
                                            LIVE
                                        </span>

                                    </td>

                                    <td class="text-right dashboard-revenue">

                                        ${{ number_format($item->real_revenue, 2) }}

                                    </td>

                                </tr>

                                @empty

                                <tr>

                                    <td
                                        colspan="3"
                                        class="status-no-data"
                                    >
                                        <i class="fa-solid fa-calendar-xmark"></i>
                                        No live event revenue yet.
                                    </td>

                                </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>


                {{-- =========================================
                     PAST EVENTS
                     ========================================= --}}
                <div class="revenue-status-section">

                    <div class="status-section-header past">

                        <div class="status-section-name">

                            <span class="status-dot past"></span>

                            Past Events

                        </div>

                        <span class="status-total past">

                            ${{ number_format(
                                collect($eventRevenueBreakdown)
                                    ->where('status', 'past')
                                    ->sum('real_revenue'),
                                2
                            ) }}

                        </span>

                    </div>


                    <div class="overflow-x-auto">

                        <table class="dashboard-modal-table">

                            <thead>

                                <tr>

                                    <th>
                                        Event
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th class="text-right">
                                        Revenue
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @php
                                    $pastRevenue = collect($eventRevenueBreakdown)
                                        ->where('status', 'past');
                                @endphp


                                @forelse($pastRevenue as $item)

                                <tr>

                                    <td class="font-semibold text-gray-800">
                                        {{ $item->title }}
                                    </td>

                                    <td>

                                        <span class="dashboard-status bg-slate-100 text-slate-600">
                                            <i class="fa-solid fa-clock-rotate-left mr-1"></i>
                                            PAST
                                        </span>

                                    </td>

                                    <td class="text-right dashboard-past-revenue">

                                        ${{ number_format($item->real_revenue, 2) }}

                                    </td>

                                </tr>

                                @empty

                                <tr>

                                    <td
                                        colspan="3"
                                        class="status-no-data"
                                    >
                                        <i class="fa-solid fa-calendar-xmark"></i>
                                        No past event revenue yet.
                                    </td>

                                </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- FOOTER --}}
            <div class="dashboard-modal-footer text-right">

                <button
                    onclick="closeModal('revenueModal')"
                    class="dashboard-close-button"
                >
                    Close
                </button>

            </div>

        </div>

    </div>


    {{-- =====================================================
         TICKETS MODAL
         LIVE + PAST SEPARATED
         ===================================================== --}}
    <div
        id="ticketsModal"
        class="dashboard-modal fixed inset-0 hidden items-center justify-center z-50 p-4"
    >

        <div class="dashboard-modal-panel max-w-3xl w-full">


            {{-- HEADER --}}
            <div class="dashboard-modal-header flex justify-between items-center">

                <h3 class="dashboard-modal-title">

                    <i class="fa-solid fa-ticket text-indigo-600"></i>

                    Ticket Sales Overview

                </h3>

                <button
                    onclick="closeModal('ticketsModal')"
                    class="dashboard-modal-close"
                >
                    &times;
                </button>

            </div>


            {{-- BODY --}}
            <div class="dashboard-modal-body">


                {{-- =========================================
                     LIVE TICKETS
                     ========================================= --}}
                <div class="ticket-status-section">

                    <div class="status-section-header live">

                        <div class="status-section-name">

                            <span class="status-dot live"></span>

                            Live Events

                        </div>

                        <span class="status-total live">

                            {{ collect($categoryTicketBreakdown)
                                ->filter(function ($cat) {
                                    return optional($cat->event)->status === 'live';
                                })
                                ->sum('paid_tickets_count')
                            }}

                            Tickets

                        </span>

                    </div>


                    <div class="overflow-x-auto">

                        <table class="dashboard-modal-table">

                            <thead>

                                <tr>

                                    <th>
                                        Event
                                    </th>

                                    <th>
                                        Category
                                    </th>

                                    <th class="text-right">
                                        Paid Tickets
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @php
                                    $liveTickets = collect($categoryTicketBreakdown)
                                        ->filter(function ($cat) {
                                            return optional($cat->event)->status === 'live';
                                        });
                                @endphp


                                @forelse($liveTickets as $cat)

                                <tr>

                                    <td class="font-medium text-gray-800">

                                        {{ $cat->event->title ?? 'N/A' }}

                                    </td>

                                    <td class="font-semibold text-indigo-600">

                                        {{ $cat->name }}

                                    </td>

                                    <td class="text-right">

                                        <span class="dashboard-ticket-number-small">

                                            {{ $cat->paid_tickets_count }}

                                        </span>

                                    </td>

                                </tr>

                                @empty

                                <tr>

                                    <td
                                        colspan="3"
                                        class="status-no-data"
                                    >
                                        <i class="fa-solid fa-ticket-slash"></i>
                                        No live event tickets sold yet.
                                    </td>

                                </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>


                {{-- =========================================
                     PAST TICKETS
                     ========================================= --}}
                <div class="ticket-status-section">

                    <div class="status-section-header past">

                        <div class="status-section-name">

                            <span class="status-dot past"></span>

                            Past Events

                        </div>

                        <span class="status-total past">

                            {{ collect($categoryTicketBreakdown)
                                ->filter(function ($cat) {
                                    return optional($cat->event)->status === 'past';
                                })
                                ->sum('paid_tickets_count')
                            }}

                            Tickets

                        </span>

                    </div>


                    <div class="overflow-x-auto">

                        <table class="dashboard-modal-table">

                            <thead>

                                <tr>

                                    <th>
                                        Event
                                    </th>

                                    <th>
                                        Category
                                    </th>

                                    <th class="text-right">
                                        Paid Tickets
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @php
                                    $pastTickets = collect($categoryTicketBreakdown)
                                        ->filter(function ($cat) {
                                            return optional($cat->event)->status === 'past';
                                        });
                                @endphp


                                @forelse($pastTickets as $cat)

                                <tr>

                                    <td class="font-medium text-gray-800">

                                        {{ $cat->event->title ?? 'N/A' }}

                                    </td>

                                    <td class="font-semibold text-slate-600">

                                        {{ $cat->name }}

                                    </td>

                                    <td class="text-right">

                                        <span class="dashboard-ticket-number-small dashboard-past-ticket-number">

                                            {{ $cat->paid_tickets_count }}

                                        </span>

                                    </td>

                                </tr>

                                @empty

                                <tr>

                                    <td
                                        colspan="3"
                                        class="status-no-data"
                                    >
                                        <i class="fa-solid fa-ticket-slash"></i>
                                        No past event tickets sold yet.
                                    </td>

                                </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- FOOTER --}}
            <div class="dashboard-modal-footer text-right">

                <button
                    onclick="closeModal('ticketsModal')"
                    class="dashboard-close-button"
                >
                    Close
                </button>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     EXISTING MODAL JAVASCRIPT
     FUNCTIONALITY UNCHANGED
     ========================================================= --}}
<script>
function openModal(id) {
    const el = document.getElementById(id);
    el.classList.remove('hidden');
    el.classList.add('flex');
}

function closeModal(id) {
    const el = document.getElementById(id);
    el.classList.remove('flex');
    el.classList.add('hidden');
}
</script>

@endsection