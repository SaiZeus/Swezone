@extends('layouts.admin')

@section('title', 'Create New Event')
@section('page-title', 'Create Marathon Event')

@section('content')

<style>
    .create-event-page {
        --event-primary: #4f46e5;
        --event-primary-dark: #4338ca;
        --event-border: #e5e7eb;
        --event-text: #172033;
        --event-muted: #718096;
        --event-bg: #f7f8fc;
    }

    .event-form-wrapper {
        max-width: 1050px;
        margin: 0 auto;
    }

    .event-page-header {
        margin-bottom: 24px;
    }

    .event-page-header h1 {
        margin: 0;
        color: #172033;
        font-size: 1.65rem;
        font-weight: 850;
        letter-spacing: -0.035em;
    }

    .event-page-header p {
        margin-top: 5px;
        color: #7b8798;
        font-size: 0.88rem;
    }

    .event-form-card {
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #e7eaf0;
        border-radius: 22px;
        box-shadow: 0 14px 45px rgba(25, 35, 55, 0.065);
    }

    .event-form-header {
        position: relative;
        overflow: hidden;
        padding: 28px 32px;
        background:
            radial-gradient(
                circle at 95% 0%,
                rgba(99, 102, 241, 0.15),
                transparent 30%
            ),
            linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
        border-bottom: 1px solid #e9ebf1;
    }

    .event-form-header::after {
        content: "";
        position: absolute;
        width: 160px;
        height: 160px;
        right: -70px;
        bottom: -100px;
        border-radius: 50%;
        background: rgba(79, 70, 229, 0.06);
    }

    .event-form-header-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .event-header-icon {
        width: 52px;
        height: 52px;
        min-width: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 15px;
        background: #eef2ff;
        color: #4f46e5;
        font-size: 1.35rem;
    }

    .event-form-header h2 {
        margin: 0;
        color: #172033;
        font-size: 1.15rem;
        font-weight: 850;
    }

    .event-form-header p {
        margin: 4px 0 0;
        color: #7b8798;
        font-size: 0.78rem;
    }

    .event-form-body {
        padding: 32px;
    }

    /* ALERTS */

    .event-alert {
        position: relative;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 16px;
        margin-bottom: 24px;
        border-radius: 12px;
        animation: eventAlertSlide .25s ease-out;
    }

    .event-alert-success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #15803d;
    }

    .event-alert-danger {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #b91c1c;
    }

    .event-alert-icon {
        width: 32px;
        height: 32px;
        min-width: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        font-size: .85rem;
    }

    .event-alert-success .event-alert-icon {
        background: #dcfce7;
        color: #16a34a;
    }

    .event-alert-danger .event-alert-icon {
        background: #fee2e2;
        color: #dc2626;
    }

    .event-alert-content {
        flex: 1;
        min-width: 0;
    }

    .event-alert-content strong {
        display: block;
        margin-bottom: 3px;
        font-size: .76rem;
        font-weight: 850;
    }

    .event-alert-content p {
        margin: 0;
        font-size: .69rem;
        line-height: 1.5;
    }

    .event-error-list {
        margin: 7px 0 0;
        padding-left: 17px;
    }

    .event-error-list li {
        margin-bottom: 3px;
        font-size: .68rem;
        line-height: 1.45;
    }

    .event-error-list li:last-child {
        margin-bottom: 0;
    }

    .event-alert-close {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 27px;
        height: 27px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 0;
        border-radius: 7px;
        background: transparent;
        color: #98a2b3;
        cursor: pointer;
        transition: all .15s ease;
    }

    .event-alert-close:hover {
        background: rgba(0, 0, 0, .05);
        color: #475467;
    }

    @keyframes eventAlertSlide {
        from {
            opacity: 0;
            transform: translateY(-7px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-section {
        margin-bottom: 30px;
    }

    .form-section-heading {
        display: flex;
        align-items: center;
        gap: 11px;
        margin-bottom: 18px;
    }

    .section-number {
        width: 31px;
        height: 31px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #eef2ff;
        color: #4f46e5;
        font-size: 0.72rem;
        font-weight: 850;
    }

    .form-section-heading h3 {
        margin: 0;
        color: #202b3d;
        font-size: 0.98rem;
        font-weight: 800;
    }

    .form-section-heading p {
        margin: 2px 0 0;
        color: #8a94a5;
        font-size: 0.72rem;
    }

    .event-label {
        display: block;
        margin-bottom: 7px;
        color: #354052;
        font-size: 0.76rem;
        font-weight: 800;
    }

    .event-input,
    .event-select,
    .event-textarea {
        width: 100%;
        border: 1px solid #dfe3ea;
        border-radius: 11px;
        background: #ffffff;
        color: #1f2937;
        font-size: 0.83rem;
        outline: none;
        transition: all 0.18s ease;
    }

    .event-input,
    .event-select {
        height: 44px;
        padding: 0 13px;
    }

    .event-textarea {
        min-height: 115px;
        padding: 12px 13px;
        resize: vertical;
    }

    .event-input:hover,
    .event-select:hover,
    .event-textarea:hover {
        border-color: #c8ced9;
    }

    .event-input:focus,
    .event-select:focus,
    .event-textarea:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.09);
        background: #fff;
    }

    .event-input::placeholder,
    .event-textarea::placeholder {
        color: #a2aab8;
    }

    .field-help {
        margin-top: 6px;
        color: #929baa;
        font-size: 0.68rem;
    }

    /* =========================================================
       OPENSTREETMAP / LEAFLET LOCATION SEARCH
       ========================================================= */

    .location-picker {
        margin-top: 10px;
        padding: 15px;
        border: 1px solid #e5e8ee;
        border-radius: 16px;
        background: #fafbfc;
    }

    .location-picker-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
    }

    .location-picker-icon {
        width: 36px;
        height: 36px;
        min-width: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #eef2ff;
        color: #4f46e5;
    }

    .location-picker-header h4 {
        margin: 0;
        color: #273245;
        font-size: 0.82rem;
        font-weight: 850;
    }

    .location-picker-header p {
        margin: 2px 0 0;
        color: #929baa;
        font-size: 0.67rem;
    }

    .location-search-wrapper {
        position: relative;
        margin-bottom: 10px;
    }

    .location-search-wrapper > i {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #8b95a5;
        font-size: 0.8rem;
        z-index: 2;
    }

    #location-search {
        padding-left: 37px;
        padding-right: 110px;
    }

    .location-search-button {
        position: absolute;
        right: 5px;
        top: 5px;
        height: 34px;
        padding: 0 13px;
        border: 0;
        border-radius: 8px;
        background: #4f46e5;
        color: #ffffff;
        font-size: 0.68rem;
        font-weight: 800;
        cursor: pointer;
        transition: all .18s ease;
        z-index: 3;
    }

    .location-search-button:hover {
        background: #4338ca;
    }

    .location-search-button:disabled {
        opacity: .65;
        cursor: not-allowed;
    }

    #event-location-map {
        width: 100%;
        height: 350px;
        overflow: hidden;
        border: 1px solid #dfe3ea;
        border-radius: 13px;
        background: #eef1f5;
    }

    .map-instruction {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-top: 9px;
        color: #7b8493;
        font-size: 0.67rem;
    }

    .map-instruction i {
        color: #6366f1;
    }

    .selected-location-box {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-top: 10px;
        padding: 10px 12px;
        border: 1px solid #dbeafe;
        border-radius: 10px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 0.69rem;
    }

    .selected-location-box i {
        font-size: 0.8rem;
    }

    .selected-location-box span {
        min-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .location-search-results {
        display: none;
        margin-bottom: 10px;
        overflow: hidden;
        border: 1px solid #e1e5eb;
        border-radius: 11px;
        background: #ffffff;
        box-shadow: 0 8px 25px rgba(30, 40, 60, .08);
    }

    .location-result {
        padding: 11px 13px;
        border-bottom: 1px solid #edf0f4;
        cursor: pointer;
        transition: background .15s ease;
    }

    .location-result:last-child {
        border-bottom: 0;
    }

    .location-result:hover {
        background: #f8f9ff;
    }

    .location-result.selected {
        background: #eef2ff;
    }

    .location-result-title {
        color: #273245;
        font-size: .73rem;
        font-weight: 800;
    }

    .location-result-address {
        margin-top: 3px;
        color: #8a94a5;
        font-size: .64rem;
        line-height: 1.4;
    }

    .location-auto-status {
        display: flex;
        align-items: center;
        gap: 6px;
        min-height: 18px;
        margin: 5px 2px 8px;
        color: #8a94a5;
        font-size: .65rem;
    }

    .location-auto-status.loading {
        color: #4f46e5;
    }

    .location-auto-status.success {
        color: #059669;
    }

    .location-auto-status.error {
        color: #dc2626;
    }

    .leaflet-popup-content {
        font-size: 12px;
        line-height: 1.5;
    }

    .leaflet-control-attribution {
        font-size: 9px !important;
    }

    /* IMAGE UPLOAD */

    .image-upload-box {
        position: relative;
        padding: 18px;
        border: 1px dashed #cfd5df;
        border-radius: 13px;
        background: #fafbfc;
        transition: all 0.2s ease;
    }

    .image-upload-box:hover {
        border-color: #818cf8;
        background: #f8f9ff;
    }

    .image-upload-content {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .image-upload-icon {
        width: 43px;
        height: 43px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        background: #eef2ff;
        color: #6366f1;
    }

    .image-upload-text {
        flex: 1;
    }

    .image-upload-text strong {
        display: block;
        margin-bottom: 3px;
        color: #364152;
        font-size: 0.78rem;
        font-weight: 800;
    }

    .image-upload-text span {
        color: #929baa;
        font-size: 0.68rem;
    }

    .event-file-input {
        width: 100%;
        margin-top: 12px;
        padding: 8px;
        border: 1px solid #e0e4ea;
        border-radius: 99px;
        background: white;
        color: #7b8493;
        font-size: 0.73rem;
    }

    /* CREATOR */

    .creator-box {
        padding: 20px;
        border: 1px solid #e5e8ee;
        border-radius: 16px;
        background: linear-gradient(135deg, #ffffff 0%, #fafbff 100%);
    }

    .creator-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 17px;
    }

    .creator-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #ecfdf5;
        color: #059669;
    }

    .creator-header h3 {
        margin: 0;
        color: #273245;
        font-size: 0.88rem;
        font-weight: 850;
    }

    .creator-header p {
        margin: 2px 0 0;
        color: #929baa;
        font-size: 0.68rem;
    }

    /* TICKET CATEGORIES */

    .ticket-builder {
        padding: 20px;
        border: 1px solid #e5e8ee;
        border-radius: 16px;
        background: #fafbfc;
    }

    .ticket-builder-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 15px;
    }

    .ticket-builder-title {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .ticket-builder-title-icon {
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #eef2ff;
        color: #4f46e5;
    }

    .ticket-builder-title h3 {
        margin: 0;
        color: #263143;
        font-size: 0.86rem;
        font-weight: 850;
    }

    .ticket-builder-title p {
        margin: 2px 0 0;
        color: #8b95a5;
        font-size: 0.68rem;
    }

    .add-category-button {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 13px;
        border: 1px solid #cfd5ff;
        border-radius: 10px;
        background: #eef2ff;
        color: #4f46e5;
        font-size: 0.72rem;
        font-weight: 800;
        transition: all 0.18s ease;
        cursor: pointer;
    }

    .add-category-button:hover {
        background: #e0e7ff;
        border-color: #a5b4fc;
        transform: translateY(-1px);
    }

    .category-row {
        position: relative;
        padding: 15px;
        border: 1px solid #e3e7ed !important;
        border-radius: 13px !important;
        background: #ffffff !important;
        box-shadow: 0 3px 12px rgba(30, 40, 60, 0.035);
    }

    .category-row input {
        height: 42px;
        width: 100%;
        padding: 0 11px;
        border: 1px solid #dfe3e9;
        border-radius: 9px;
        outline: none;
        background: #fff;
        color: #293445;
        font-size: 0.76rem;
    }

    .category-row input:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.08);
    }

    .remove-category-btn {
        width: 38px;
        height: 38px;
        min-width: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #fee2e2;
        border-radius: 9px;
        background: #fff5f5;
        color: #ef4444;
        cursor: pointer;
    }

    /* WAIVER & CONSENT */

    .waiver-box {
        padding: 20px;
        border: 1px solid #e5e8ee;
        border-radius: 16px;
        background: #fafbfc;
    }

    .waiver-info {
        display: flex;
        gap: 10px;
        align-items: flex-start;
        margin-bottom: 17px;
        padding: 12px 14px;
        border-radius: 10px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 0.72rem;
    }

    .waiver-file {
        padding: 13px;
        border: 1px solid #e3e7ed;
        border-radius: 11px;
        background: white;
    }

    .waiver-file label {
        margin-bottom: 7px;
    }

    /* EVENT ITEMS */

    .items-builder {
        padding: 20px;
        border: 1px solid #e5e8ee;
        border-radius: 16px;
        background: #fafbfc;
    }

    .item-row {
        display: grid;
        grid-template-columns: 1fr 1fr 45px;
        gap: 12px;
        align-items: end;
        padding: 15px;
        margin-bottom: 12px;
        border: 1px solid #e3e7ed;
        border-radius: 13px;
        background: #fff;
    }

    .item-row.removing {
        opacity: 0;
        transform: scale(.98);
        transition: all .15s ease;
    }

    .add-item-button {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 13px;
        border: 1px solid #d1fae5;
        border-radius: 10px;
        background: #ecfdf5;
        color: #059669;
        font-size: 0.72rem;
        font-weight: 800;
        cursor: pointer;
    }

    .remove-item-btn {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #fee2e2;
        border-radius: 99px;
        background: #fff5f5;
        color: #ef4444;
        cursor: pointer;
    }

    .remove-item-btn:hover {
        background: #fee2e2;
        color: #dc2626;
    }

    .empty-items-message {
        display: none;
        padding: 18px;
        margin-bottom: 12px;
        border: 1px dashed #dfe3ea;
        border-radius: 12px;
        background: #fff;
        color: #929baa;
        text-align: center;
        font-size: .72rem;
    }

    .event-divider {
        height: 1px;
        margin: 30px 0;
        border: 0;
        background: #eaedf2;
    }

    .publish-button {
        position: relative;
        width: 100%;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        border: 0;
        border-radius: 12px;
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        color: white;
        font-size: 0.84rem;
        font-weight: 850;
        box-shadow: 0 9px 22px rgba(79, 70, 229, 0.22);
        transition: all 0.18s ease;
        cursor: pointer;
    }

    .publish-button:hover {
        transform: translateY(-2px);
        filter: brightness(1.03);
        box-shadow: 0 13px 28px rgba(79, 70, 229, 0.3);
    }

    /* FIELD TOGGLE BOX */

    .fields-toggle-box {
        padding: 20px;
        border: 1px solid #e5e8ee;
        border-radius: 16px;
        background: #fafbfc;
    }

    .toggle-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 12px;
        margin-top: 12px;
    }

    .toggle-card {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        background: #ffffff;
        cursor: pointer;
        font-size: 0.78rem;
        font-weight: 700;
        color: #374151;
    }

    .toggle-card input[type="checkbox"] {
        accent-color: #4f46e5;
        width: 16px;
        height: 16px;
    }

    @media (max-width: 767px) {
        .event-form-body {
            padding: 20px;
        }

        .event-form-header {
            padding: 22px 20px;
        }

        .ticket-builder,
        .items-builder,
        .waiver-box,
        .creator-box,
        .fields-toggle-box {
            padding: 15px;
        }

        .ticket-builder-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .add-category-button {
            width: 100%;
            justify-content: center;
        }

        .item-row {
            grid-template-columns: 1fr;
        }

        #event-location-map {
            height: 280px;
        }

        #location-search {
            padding-right: 95px;
        }

        .location-search-button {
            padding: 0 10px;
        }
    }
</style>

{{-- =============================================================
LEAFLET CSS
============================================================= --}}

<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>

<div class="create-event-page">
    <div class="event-form-wrapper">

```
    <div class="event-page-header">
        <h1>Create Marathon Event</h1>
        <p>
            Set up your event details, tickets, capacity and participant information.
        </p>
    </div>

    <div class="event-form-card">

        <div class="event-form-header">
            <div class="event-form-header-content">

                <div class="event-header-icon">
                    <i class="fa-solid fa-person-running"></i>
                </div>

                <div>
                    <h2>New Marathon Event</h2>
                    <p>
                        Complete the information below to publish your event.
                    </p>
                </div>

            </div>
        </div>

        <div class="event-form-body">

            {{-- SUCCESS MESSAGE --}}
            @if(session('success'))
                <div class="event-alert event-alert-success">

                    <div class="event-alert-icon">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>

                    <div class="event-alert-content">
                        <strong>Success</strong>
                        <p>{{ session('success') }}</p>
                    </div>

                    <button
                        type="button"
                        class="event-alert-close"
                        onclick="this.parentElement.remove()">
                        <i class="fa-solid fa-xmark"></i>
                    </button>

                </div>
            @endif

            {{-- GENERAL ERROR MESSAGE --}}
            @if(session('error'))
                <div class="event-alert event-alert-danger">

                    <div class="event-alert-icon">
                        <i class="fa-solid fa-circle-exclamation"></i>
                    </div>

                    <div class="event-alert-content">
                        <strong>Event Could Not Be Published</strong>
                        <p>{{ session('error') }}</p>
                    </div>

                    <button
                        type="button"
                        class="event-alert-close"
                        onclick="this.parentElement.remove()">
                        <i class="fa-solid fa-xmark"></i>
                    </button>

                </div>
            @endif

            {{-- VALIDATION ERRORS --}}
            @if($errors->any())
                <div class="event-alert event-alert-danger">

                    <div class="event-alert-icon">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>

                    <div class="event-alert-content">

                        <strong>Please fix the following errors:</strong>

                        <ul class="event-error-list">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                    </div>

                    <button
                        type="button"
                        class="event-alert-close"
                        onclick="this.parentElement.remove()">
                        <i class="fa-solid fa-xmark"></i>
                    </button>

                </div>
            @endif

            <form
                action="{{ route('admin.events.store') }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf

                {{-- =====================================================
                     SECTION 01 - EVENT INFORMATION
                ====================================================== --}}

                <div class="form-section">

                    <div class="form-section-heading">

                        <div class="section-number">01</div>

                        <div>
                            <h3>Event Information</h3>
                            <p>Basic details about your marathon event.</p>
                        </div>

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- EVENT TITLE --}}
                        <div>
                            <label class="event-label">
                                Event Title
                            </label>

                            <input
                                type="text"
                                name="title"
                                required
                                value="{{ old('title') }}"
                                placeholder="e.g. Yangon International Marathon"
                                class="event-input">
                        </div>

                        {{-- LOCATION --}}
                        <div>

                            <label class="event-label">
                                Location
                            </label>

                            <input
                                type="text"
                                name="location"
                                id="location"
                                required
                                value="{{ old('location') }}"
                                placeholder="e.g. Shwedagon Pagoda, Yangon"
                                class="event-input">

                            {{-- OPENSTREETMAP LOCATION PICKER --}}
                            <div class="location-picker">

                                <div class="location-picker-header">

                                    <div class="location-picker-icon">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </div>

                                    <div>
                                        <h4>Find Event Location</h4>
                                        <p>
                                            Type a venue, street or city. The map will locate it automatically.
                                        </p>
                                    </div>

                                </div>

                                <div class="location-search-wrapper">

                                    <i class="fa-solid fa-magnifying-glass"></i>

                                    <input
                                        type="text"
                                        id="location-search"
                                        class="event-input"
                                        value="{{ old('location') }}"
                                        placeholder="Type a venue, street or city..."
                                        autocomplete="off">

                                    <button
                                        type="button"
                                        id="location-search-button"
                                        class="location-search-button">
                                        Search
                                    </button>

                                </div>

                                <div
                                    id="location-auto-status"
                                    class="location-auto-status">
                                </div>

                                <div
                                    id="location-search-results"
                                    class="location-search-results">
                                </div>

                                <div id="event-location-map"></div>

                                <div class="map-instruction">

                                    <i class="fa-solid fa-circle-info"></i>

                                    <span>
                                        Type the location above and the map will automatically point to the place. Select a result if you need a more exact venue.
                                    </span>

                                </div>

                                <div
                                    id="selected-location-box"
                                    class="selected-location-box"
                                    style="display: none;">

                                    <i class="fa-solid fa-location-crosshairs"></i>

                                    <span id="selected-location-text">
                                        Location selected
                                    </span>

                                </div>

                            </div>

                            {{-- HIDDEN COORDINATES --}}
                            <input
                                type="hidden"
                                name="latitude"
                                id="latitude"
                                value="{{ old('latitude') }}">

                            <input
                                type="hidden"
                                name="longitude"
                                id="longitude"
                                value="{{ old('longitude') }}">

                        </div>

                        {{-- EVENT DATE --}}
                        <div>

                            <label class="event-label">
                                Event Date & Time
                            </label>

                            <input
                                type="datetime-local"
                                name="event_date"
                                required
                                value="{{ old('event_date') }}"
                                class="event-input">

                        </div>

                        {{-- STATUS --}}
                        <div>

                            <label class="event-label">
                                Status
                            </label>

                            <select
                                name="status"
                                class="event-select">

                                <option
                                    value="upcoming"
                                    {{ old('status') === 'upcoming' ? 'selected' : '' }}>
                                    Upcoming
                                </option>

                                <option
                                    value="live"
                                    {{ old('status') === 'live' ? 'selected' : '' }}>
                                    Live Now
                                </option>

                                <option
                                    value="past"
                                    {{ old('status') === 'past' ? 'selected' : '' }}>
                                    Past
                                </option>

                            </select>

                        </div>

                        {{-- CAPACITY --}}
                        <div class="md:col-span-2">

                            <label class="event-label">
                                Overall Event Capacity
                            </label>

                            <input
                                type="number"
                                name="overall_capacity"
                                min="1"
                                value="{{ old('overall_capacity') }}"
                                placeholder="e.g. 5000"
                                class="event-input">

                            <div class="field-help">
                                Optional. This limits the total number of participants across all ticket types. Leave empty for unlimited event capacity.
                            </div>

                        </div>

                    </div>

                </div>

                <hr class="event-divider">

                {{-- =====================================================
                     SECTION 02 - FORM FIELDS & BIB
                ====================================================== --}}

                <div class="form-section">

                    <div class="form-section-heading">

                        <div class="section-number">02</div>

                        <div>
                            <h3>Form Customization & BIB Setup</h3>
                            <p>
                                Choose which fields appear on the registration form and configure BIB sequence generation.
                            </p>
                        </div>

                    </div>

                    <div class="fields-toggle-box mb-4">

                        <label class="event-label mb-2">
                            Display Fields in Registration Form
                        </label>

                        <div class="toggle-grid">

                            @php
                                $availableFields = [
                                    'viber' => 'Viber Number',
                                    'father_name' => 'Father Name',
                                    'blood_type' => 'Blood Type',
                                    'tshirt_size' => 'T-Shirt Size',
                                    'has_medical_condition' => 'Medical Condition',
                                    'itra' => 'ITRA Details',
                                    'experience' => 'Running Experience',
                                    'address' => 'Address'
                                ];
                            @endphp

                            @foreach($availableFields as $fieldKey => $fieldLabel)

                                <label class="toggle-card">

                                    <input
                                        type="checkbox"
                                        name="enabled_fields[]"
                                        value="{{ $fieldKey }}"
                                        checked>

                                    <span>{{ $fieldLabel }}</span>

                                </label>

                            @endforeach

                        </div>

                    </div>

                    <div class="fields-toggle-box">

                        <div class="flex items-center gap-3 mb-3">

                            <input
                                type="checkbox"
                                name="enable_bib_number"
                                id="enable_bib_number"
                                value="1"
                                checked
                                class="w-4 h-4 accent-indigo-600">

                            <label
                                for="enable_bib_number"
                                class="event-label mb-0 cursor-pointer">

                                Enable Automatic BIB Generation

                            </label>

                        </div>

                        <div
                            id="bib_config_wrapper"
                            class="mt-3 border-t border-gray-200 pt-3">

                            <label class="event-label mb-2">
                                BIB Prefix Mode
                            </label>

                            <div class="flex gap-4 mb-4">

                                <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-gray-700">

                                    <input
                                        type="radio"
                                        name="share_bib_prefix"
                                        value="1"
                                        checked
                                        class="accent-indigo-600">

                                    Entire Event Shares Same Prefix (e.g. NA-0001)

                                </label>

                                <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-gray-700">

                                    <input
                                        type="radio"
                                        name="share_bib_prefix"
                                        value="0"
                                        class="accent-indigo-600">

                                    Separate Prefix Per Ticket Category

                                </label>

                            </div>

                            <div
                                id="shared_prefix_box"
                                class="grid grid-cols-1 md:grid-cols-2 gap-3">

                                <div>

                                    <label class="event-label">
                                        Event BIB Prefix (Max 3 Chars)
                                    </label>

                                    <input
                                        type="text"
                                        name="event_bib_prefix"
                                        maxlength="3"
                                        placeholder="e.g. NA"
                                        class="event-input uppercase">

                                </div>

                                <div>

                                    <label class="event-label">
                                        Start Number
                                    </label>

                                    <input
                                        type="number"
                                        name="event_bib_start_number"
                                        value="1"
                                        min="1"
                                        class="event-input">

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <hr class="event-divider">

                {{-- =====================================================
                     SECTION 03 - EVENT CREATOR
                ====================================================== --}}

                <div class="form-section">

                    <div class="form-section-heading">

                        <div class="section-number">03</div>

                        <div>
                            <h3>Event Creator</h3>
                            <p>
                                Contact information for the person responsible for this event.
                            </p>
                        </div>

                    </div>

                    <div class="creator-box">

                        <div class="creator-header">

                            <div class="creator-icon">
                                <i class="fa-solid fa-user-tie"></i>
                            </div>

                            <div>
                                <h3>Event Organizer / Creator</h3>
                                <p>
                                    Clients can use this information to contact the event creator.
                                </p>
                            </div>

                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            <div>

                                <label class="event-label">
                                    Creator Name
                                </label>

                                <input
                                    type="text"
                                    name="creator_name"
                                    value="{{ old('creator_name') }}"
                                    placeholder="e.g. John Doe"
                                    class="event-input"
                                    required>

                            </div>

                            <div>

                                <label class="event-label">
                                    Creator Phone Number
                                </label>

                                <input
                                    type="text"
                                    name="creator_phone"
                                    value="{{ old('creator_phone') }}"
                                    placeholder="e.g. 09 123 456 789"
                                    class="event-input"
                                    required>

                            </div>

                            <div>

                                <label class="event-label">
                                    Creator Email
                                </label>

                                <input
                                    type="email"
                                    name="creator_email"
                                    value="{{ old('creator_email') }}"
                                    placeholder="organizer@example.com"
                                    class="event-input"
                                    required>

                            </div>

                        </div>

                    </div>

                </div>

                <hr class="event-divider">

                {{-- =====================================================
                     SECTION 04 - DESCRIPTION
                ====================================================== --}}

                <div class="form-section">

                    <div class="form-section-heading">

                        <div class="section-number">04</div>

                        <div>
                            <h3>Event Description</h3>
                            <p>
                                Tell runners what they need to know about this event.
                            </p>
                        </div>

                    </div>

                    <div>

                        <label class="event-label">
                            Description
                        </label>

                        <textarea
                            name="description"
                            rows="4"
                            required
                            placeholder="Write a description about your marathon event..."
                            class="event-textarea">{{ old('description') }}</textarea>

                    </div>

                </div>

                <hr class="event-divider">

                {{-- =====================================================
                     SECTION 05 - EVENT BANNER
                ====================================================== --}}

                <div class="form-section">

                    <div class="form-section-heading">

                        <div class="section-number">05</div>

                        <div>
                            <h3>Event Banner</h3>
                            <p>
                                Upload an image to represent your event.
                            </p>
                        </div>

                    </div>

                    <div class="image-upload-box">

                        <div class="image-upload-content">

                            <div class="image-upload-icon">
                                <i class="fa-solid fa-image"></i>
                            </div>

                            <div class="image-upload-text">

                                <strong>Event Banner Image</strong>

                                <span>
                                    Choose a high-quality image for your marathon.
                                </span>

                            </div>

                        </div>

                        <input
                            type="file"
                            name="image"
                            accept="image/jpeg,image/png,image/jpg,image/gif"
                            class="event-file-input">

                    </div>

                </div>

                <hr class="event-divider">

                {{-- =====================================================
                     SECTION 06 - TICKET CATEGORIES
                ====================================================== --}}

                <div class="form-section">

                    <div class="form-section-heading">

                        <div class="section-number">06</div>

                        <div>
                            <h3>Ticket Categories</h3>
                            <p>
                                Create ticket types, prices and participant capacity.
                            </p>
                        </div>

                    </div>

                    <div class="ticket-builder">

                        <div class="ticket-builder-header">

                            <div class="ticket-builder-title">

                                <div class="ticket-builder-title-icon">
                                    <i class="fa-solid fa-ticket"></i>
                                </div>

                                <div>
                                    <h3>Ticket Types</h3>
                                    <p>
                                        Add different distances or registration options.
                                    </p>
                                </div>

                            </div>

                            <button
                                type="button"
                                id="add-category-btn"
                                class="add-category-button">

                                <i class="fa-solid fa-plus"></i>
                                Add Another Ticket Type

                            </button>

                        </div>

                        <div
                            id="categories-container"
                            class="space-y-3">

                            <div class="category-row grid grid-cols-1 md:grid-cols-6 gap-3">

                                <input
                                    type="text"
                                    name="categories[0][name]"
                                    placeholder="Category (e.g., 10km)"
                                    required
                                    class="md:col-span-2">

                                <input
                                    type="number"
                                    step="0.01"
                                    name="categories[0][local_price]"
                                    placeholder="Local Price (MMK)"
                                    required>

                                <input
                                    type="number"
                                    step="0.01"
                                    name="categories[0][foreign_price]"
                                    placeholder="Foreign Price (Optional)">

                                <input
                                    type="number"
                                    name="categories[0][capacity]"
                                    min="1"
                                    placeholder="Capacity">

                                <div
                                    class="flex items-center space-x-2 separate-bib-inputs"
                                    style="display:none;">

                                    <input
                                        type="text"
                                        name="categories[0][bib_prefix]"
                                        maxlength="3"
                                        placeholder="Prefix"
                                        class="uppercase">

                                    <input
                                        type="number"
                                        name="categories[0][bib_start_number]"
                                        value="1"
                                        min="1"
                                        placeholder="Start #">

                                    <button
                                        type="button"
                                        class="remove-category-btn"
                                        title="Delete ticket category">

                                        <i class="fa-solid fa-trash"></i>

                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <hr class="event-divider">

                {{-- =====================================================
                     SECTION 07 - EVENT ITEMS
                ====================================================== --}}

                <div class="form-section">

                    <div class="form-section-heading">

                        <div class="section-number">07</div>

                        <div>
                            <h3>Event Items</h3>
                            <p>
                                Add any items or benefits included with the event.
                            </p>
                        </div>

                    </div>

                    <div class="items-builder">

                        <div class="promo-heading">

                            <div
                                class="promo-icon"
                                style="background:#ecfdf5;color:#059669;">

                                <i class="fa-solid fa-gift"></i>

                            </div>

                            <div>
                                <h3>What's Included?</h3>
                                <p>
                                    These items are independent from ticket categories.
                                </p>
                            </div>

                        </div>

                        <div id="items-container">

                            @php
                                /*
                                 * If validation failed, rebuild only the
                                 * item rows that were actually submitted.
                                 *
                                 * This prevents deleted rows from being
                                 * recreated from the old request.
                                 */
                                $oldItems = old('items');

                                if (is_array($oldItems) && count($oldItems) > 0) {
                                    $itemsForDisplay = $oldItems;
                                } else {
                                    $itemsForDisplay = [
                                        0 => [
                                            'title' => ''
                                        ]
                                    ];
                                }
                            @endphp

                            @foreach($itemsForDisplay as $index => $oldItem)

                                <div class="item-row">

                                    <div>

                                        <label class="event-label">
                                            Item Title
                                        </label>

                                        <input
                                            type="text"
                                            name="items[{{ $index }}][title]"
                                            value="{{ old("items.$index.title", $oldItem['title'] ?? '') }}"
                                            placeholder="e.g. Running Shirt"
                                            class="event-input">

                                    </div>

                                    <div>

                                        <label class="event-label">
                                            Item Image
                                        </label>

                                        <input
                                            type="file"
                                            name="items[{{ $index }}][image]"
                                            accept="image/jpeg,image/png,image/jpg,image/gif"
                                            class="event-file-input">

                                    </div>

                                    <button
                                        type="button"
                                        class="remove-item-btn"
                                        title="Delete event item">

                                        <i class="fa-solid fa-trash"></i>

                                    </button>

                                </div>

                            @endforeach

                        </div>

                        <div
                            id="empty-items-message"
                            class="empty-items-message">

                            <i class="fa-solid fa-box-open me-1"></i>
                            No event items added. Click "Add Another Item" if you want to include an item.

                        </div>

                        <button
                            type="button"
                            id="add-item-btn"
                            class="add-item-button">

                            <i class="fa-solid fa-plus"></i>
                            Add Another Item

                        </button>

                    </div>

                </div>

                <hr class="event-divider">

                {{-- =====================================================
                     SECTION 08 - WAIVERS
                ====================================================== --}}

                <div class="form-section">

                    <div class="form-section-heading">

                        <div class="section-number">08</div>

                        <div>
                            <h3>
                                Participant Waivers, Consents & Race Guides
                            </h3>

                            <p>
                                Upload rules, terms, consent forms, and event guides for registered runners.
                            </p>
                        </div>

                    </div>

                    <div class="waiver-box">

                        <div class="waiver-info">

                            <i class="fa-solid fa-circle-info"></i>

                            <div>
                                Participants will see the waiver and optional consent forms before completing payment. Race guides provide additional event day details. English and Burmese PDF documents can be uploaded separately. If no consent form PDF is uploaded, it will be skipped automatically.
                            </div>

                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            {{-- ENGLISH WAIVER --}}
                            <div class="waiver-file">

                                <label class="event-label">
                                    English Waiver PDF
                                </label>

                                <input
                                    type="file"
                                    name="english_waiver"
                                    accept="application/pdf"
                                    class="event-file-input">

                                <div class="field-help">
                                    Optional PDF document.
                                </div>

                            </div>

                            {{-- BURMESE WAIVER --}}
                            <div class="waiver-file">

                                <label class="event-label">
                                    Burmese Waiver PDF
                                </label>

                                <input
                                    type="file"
                                    name="burmese_waiver"
                                    accept="application/pdf"
                                    class="event-file-input">

                                <div class="field-help">
                                    Optional PDF document.
                                </div>

                            </div>

                            {{-- ENGLISH CONSENT --}}
                            <div class="waiver-file">

                                <label class="event-label">
                                    English Consent Form PDF (Optional)
                                </label>

                                <input
                                    type="file"
                                    name="english_consent"
                                    accept="application/pdf"
                                    class="event-file-input">

                                <div class="field-help">
                                    Optional downloadable consent form. Skipped if left empty.
                                </div>

                            </div>

                            {{-- BURMESE CONSENT --}}
                            <div class="waiver-file">

                                <label class="event-label">
                                    Burmese Consent Form PDF (Optional)
                                </label>

                                <input
                                    type="file"
                                    name="burmese_consent"
                                    accept="application/pdf"
                                    class="event-file-input">

                                <div class="field-help">
                                    Optional downloadable consent form. Skipped if left empty.
                                </div>

                            </div>

                            {{-- ENGLISH RACE GUIDE --}}
                            <div class="waiver-file">

                                <label class="event-label">
                                    English Race Guide PDF (Optional)
                                </label>

                                <input
                                    type="file"
                                    name="english_race_guide"
                                    accept="application/pdf"
                                    class="event-file-input">

                                <div class="field-help">
                                    Optional PDF document for race instructions.
                                </div>

                            </div>

                            {{-- BURMESE RACE GUIDE --}}
                            <div class="waiver-file">

                                <label class="event-label">
                                    Burmese Race Guide PDF (Optional)
                                </label>

                                <input
                                    type="file"
                                    name="burmese_race_guide"
                                    accept="application/pdf"
                                    class="event-file-input">

                                <div class="field-help">
                                    Optional PDF document for race instructions.
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- PUBLISH BUTTON --}}
                <div class="pt-2">

                    <button
                        type="submit"
                        class="publish-button">

                        <i class="fa-solid fa-rocket"></i>
                        Publish Event

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
```

</div>

{{-- =============================================================
LEAFLET JAVASCRIPT
============================================================= --}}

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
     * ============================================================
     * OPENSTREETMAP / LEAFLET AUTOMATIC LOCATION SEARCH
     * ============================================================
     *
     * User types a location.
     * After a short delay, Nominatim searches automatically.
     * The first/best result is placed on the map.
     *
     * The user can also click Search or press Enter.
     *
     * No map dragging/clicking is required.
     */

    const mapElement =
        document.getElementById('event-location-map');

    if (!mapElement || typeof L === 'undefined') {
        return;
    }

    const locationInput =
        document.getElementById('location');

    const searchInput =
        document.getElementById('location-search');

    const searchButton =
        document.getElementById('location-search-button');

    const searchResults =
        document.getElementById('location-search-results');

    const latitudeInput =
        document.getElementById('latitude');

    const longitudeInput =
        document.getElementById('longitude');

    const selectedLocationBox =
        document.getElementById('selected-location-box');

    const selectedLocationText =
        document.getElementById('selected-location-text');

    const autoStatus =
        document.getElementById('location-auto-status');


    /*
     * Existing coordinates after validation error.
     */
    const oldLatitude =
        parseFloat(@json(old('latitude')));

    const oldLongitude =
        parseFloat(@json(old('longitude')));


    /*
     * Default location: Yangon.
     */
    const defaultLatitude =
        Number.isFinite(oldLatitude)
            ? oldLatitude
            : 16.8409;

    const defaultLongitude =
        Number.isFinite(oldLongitude)
            ? oldLongitude
            : 96.1735;


    /*
     * ============================================================
     * CREATE MAP
     * ============================================================
     */

    const eventMap =
        L.map('event-location-map', {
            zoomControl: true,
            scrollWheelZoom: true,
            dragging: true
        }).setView(
            [
                defaultLatitude,
                defaultLongitude
            ],
            Number.isFinite(oldLatitude) &&
            Number.isFinite(oldLongitude)
                ? 16
                : 12
        );


    /*
     * OpenStreetMap tiles.
     */
    L.tileLayer(
        'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            maxZoom: 19,

            attribution:
                '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a> contributors'
        }
    ).addTo(eventMap);


    /*
     * ============================================================
     * READ-ONLY MARKER
     * ============================================================
     */

    const eventMarker =
        L.marker(
            [
                defaultLatitude,
                defaultLongitude
            ],
            {
                draggable: false
            }
        ).addTo(eventMap);


    /*
     * ============================================================
     * STATUS
     * ============================================================
     */

    function setAutoStatus(
        message,
        type = ''
    ) {

        if (!autoStatus) {
            return;
        }

        autoStatus.className =
            'location-auto-status ' + type;

        autoStatus.innerHTML =
            message;

    }


    /*
     * ============================================================
     * UPDATE COORDINATES
     * ============================================================
     */

    function updateCoordinates(
        latitude,
        longitude
    ) {

        latitudeInput.value =
            Number(latitude).toFixed(7);

        longitudeInput.value =
            Number(longitude).toFixed(7);

    }


    /*
     * ============================================================
     * SHOW SELECTED LOCATION
     * ============================================================
     */

    function showSelectedLocation(
        text
    ) {

        if (!selectedLocationBox || !selectedLocationText) {
            return;
        }

        selectedLocationBox.style.display =
            'flex';

        selectedLocationText.textContent =
            text;

    }


    /*
     * ============================================================
     * SET MAP LOCATION
     * ============================================================
     */

    function setMapLocation(
        latitude,
        longitude,
        locationName = ''
    ) {

        latitude =
            Number(latitude);

        longitude =
            Number(longitude);


        if (
            !Number.isFinite(latitude) ||
            !Number.isFinite(longitude)
        ) {
            return;
        }


        /*
         * Save coordinates.
         */
        updateCoordinates(
            latitude,
            longitude
        );


        /*
         * Move marker.
         */
        eventMarker.setLatLng([
            latitude,
            longitude
        ]);


        /*
         * Move map.
         */
        eventMap.setView(
            [
                latitude,
                longitude
            ],
            16,
            {
                animate: true
            }
        );


        /*
         * Update selected location.
         */
        if (locationName) {

            locationInput.value =
                locationName;

            searchInput.value =
                locationName;

            showSelectedLocation(
                locationName
            );

        }


        /*
         * Popup.
         */
        const popupTitle =
            locationName || 'Event Location';

        eventMarker
            .bindPopup(
                `<strong>${escapeHtml(popupTitle)}</strong>`
            )
            .openPopup();


        setAutoStatus(
            '<i class="fa-solid fa-circle-check"></i> Location found and coordinates saved automatically.',
            'success'
        );

    }


    /*
     * ============================================================
     * SEARCH LOCATION
     * ============================================================
     */

    let searchTimer = null;

    let currentSearchController = null;


    async function searchLocation(
        automatic = false
    ) {

        const query =
            searchInput.value.trim();


        if (!query) {

            if (!automatic) {
                searchInput.focus();
            }

            return;

        }


        /*
         * Avoid unnecessary API requests for very short text.
         */
        if (query.length < 3) {

            if (automatic) {
                return;
            }

            setAutoStatus(
                '<i class="fa-solid fa-circle-info"></i> Type at least 3 characters.',
                ''
            );

            return;

        }


        /*
         * Cancel previous request.
         */
        if (currentSearchController) {
            currentSearchController.abort();
        }

        currentSearchController =
            new AbortController();


        if (!automatic) {

            searchButton.disabled =
                true;

            searchButton.textContent =
                'Searching...';

        }


        setAutoStatus(
            '<i class="fa-solid fa-spinner fa-spin"></i> Finding location...',
            'loading'
        );


        searchResults.innerHTML =
            '';

        searchResults.style.display =
            'none';


        try {

            const response =
                await fetch(
                    'https://nominatim.openstreetmap.org/search?' +
                    new URLSearchParams({
                        format: 'jsonv2',
                        q: query,
                        limit: 5,
                        addressdetails: 1
                    }),
                    {
                        headers: {
                            'Accept':
                                'application/json'
                        },
                        signal:
                            currentSearchController.signal
                    }
                );


            if (!response.ok) {

                throw new Error(
                    'Location search failed.'
                );

            }


            const results =
                await response.json();


            /*
             * No result.
             */
            if (!results.length) {

                setAutoStatus(
                    '<i class="fa-solid fa-circle-exclamation"></i> Location not found. Try adding the city or country.',
                    'error'
                );


                searchResults.innerHTML = `
                    <div class="location-result">
                        <div class="location-result-title">
                            No locations found
                        </div>

                        <div class="location-result-address">
                            Try a more specific venue, street or city name.
                        </div>
                    </div>
                `;

                searchResults.style.display =
                    'block';

                return;

            }


            /*
             * ====================================================
             * AUTOMATICALLY SELECT BEST RESULT
             * ====================================================
             *
             * This is the important part:
             *
             * User types:
             * "Shwedagon Pagoda Yangon"
             *
             * The first/best Nominatim result is automatically
             * placed on the map.
             */

            const bestResult =
                results[0];

            const bestLatitude =
                parseFloat(bestResult.lat);

            const bestLongitude =
                parseFloat(bestResult.lon);

            const bestLocationName =
                bestResult.display_name ||
                bestResult.name ||
                query;


            setMapLocation(
                bestLatitude,
                bestLongitude,
                bestLocationName
            );


            /*
             * ====================================================
             * DISPLAY ALL SEARCH RESULTS
             * ====================================================
             */

            results.forEach(
                function (result, index) {

                    const resultElement =
                        document.createElement('div');

                    resultElement.className =
                        'location-result' +
                        (index === 0 ? ' selected' : '');


                    const title =
                        result.name ||
                        'Selected Location';


                    const address =
                        result.display_name ||
                        '';


                    resultElement.innerHTML = `
                        <div class="location-result-title">
                            ${index === 0 ? '<i class="fa-solid fa-check me-1"></i>' : ''}
                            ${escapeHtml(title)}
                        </div>

                        <div class="location-result-address">
                            ${escapeHtml(address)}
                        </div>
                    `;


                    /*
                     * Select exact result manually.
                     */
                    resultElement.addEventListener(
                        'click',
                        function () {

                            const latitude =
                                parseFloat(result.lat);

                            const longitude =
                                parseFloat(result.lon);

                            const locationName =
                                result.display_name ||
                                result.name ||
                                query;


                            setMapLocation(
                                latitude,
                                longitude,
                                locationName
                            );


                            searchResults.style.display =
                                'none';

                        }
                    );


                    searchResults.appendChild(
                        resultElement
                    );

                }
            );


            /*
             * Show results for a short time.
             */
            searchResults.style.display =
                'block';


        } catch (error) {

            /*
             * AbortError is normal when the user keeps typing.
             */
            if (error.name === 'AbortError') {
                return;
            }


            console.error(
                'Location search error:',
                error
            );


            setAutoStatus(
                '<i class="fa-solid fa-circle-exclamation"></i> Unable to search location. Please try again.',
                'error'
            );


            searchResults.innerHTML = `
                <div class="location-result">
                    <div class="location-result-title">
                        Unable to search location
                    </div>

                    <div class="location-result-address">
                        Please try again.
                    </div>
                </div>
            `;

            searchResults.style.display =
                'block';


        } finally {

            if (!automatic) {

                searchButton.disabled =
                    false;

                searchButton.textContent =
                    'Search';

            }

        }

    }


    /*
     * ============================================================
     * AUTOMATIC SEARCH WHILE TYPING
     * ============================================================
     */

    searchInput.addEventListener(
        'input',
        function () {

            const query =
                this.value.trim();


            /*
             * Keep main Location field synchronized.
             */
            locationInput.value =
                query;


            /*
             * Clear coordinates while the user is typing a
             * different location.
             *
             * This prevents old coordinates from being submitted
             * for a newly typed location.
             */
            if (query.length >= 3) {

                latitudeInput.value =
                    '';

                longitudeInput.value =
                    '';

            }


            clearTimeout(searchTimer);


            if (query.length < 3) {

                searchResults.style.display =
                    'none';

                setAutoStatus(
                    '',
                    ''
                );

                return;

            }


            /*
             * Wait 800ms after typing stops.
             */
            searchTimer =
                setTimeout(
                    function () {

                        searchLocation(true);

                    },
                    800
                );

        }
    );


    /*
     * ============================================================
     * SEARCH BUTTON
     * ============================================================
     */

    searchButton.addEventListener(
        'click',
        function () {

            clearTimeout(searchTimer);

            searchLocation(false);

        }
    );


    /*
     * ============================================================
     * ENTER KEY
     * ============================================================
     */

    searchInput.addEventListener(
        'keydown',
        function (event) {

            if (event.key === 'Enter') {

                event.preventDefault();

                clearTimeout(searchTimer);

                searchLocation(false);

            }

        }
    );


    /*
     * ============================================================
     * MAIN LOCATION FIELD
     * ============================================================
     *
     * If the user types directly into the main Location field,
     * keep the search box synchronized.
     */

    locationInput.addEventListener(
        'input',
        function () {

            searchInput.value =
                this.value;

            clearTimeout(searchTimer);


            const query =
                this.value.trim();


            if (query.length < 3) {

                searchResults.style.display =
                    'none';

                return;

            }


            latitudeInput.value =
                '';

            longitudeInput.value =
                '';


            searchTimer =
                setTimeout(
                    function () {

                        searchLocation(true);

                    },
                    800
                );

        }
    );


    /*
     * ============================================================
     * CLOSE SEARCH RESULTS
     * ============================================================
     */

    document.addEventListener(
        'click',
        function (event) {

            if (
                !event.target.closest(
                    '.location-search-wrapper'
                ) &&
                !event.target.closest(
                    '#location-search-results'
                )
            ) {

                searchResults.style.display =
                    'none';

            }

        }
    );


    /*
     * ============================================================
     * HTML ESCAPE
     * ============================================================
     */

    function escapeHtml(value) {

        const div =
            document.createElement('div');

        div.textContent =
            value;

        return div.innerHTML;

    }


    /*
     * ============================================================
     * LOAD OLD LOCATION AFTER VALIDATION ERROR
     * ============================================================
     */

    if (
        Number.isFinite(oldLatitude) &&
        Number.isFinite(oldLongitude)
    ) {

        updateCoordinates(
            oldLatitude,
            oldLongitude
        );


        const oldLocation =
            locationInput.value.trim();


        if (oldLocation) {

            searchInput.value =
                oldLocation;

            showSelectedLocation(
                oldLocation
            );


            eventMarker
                .bindPopup(
                    `<strong>${escapeHtml(oldLocation)}</strong>`
                );

        }

    }


    /*
     * If old location exists but coordinates don't,
     * automatically search it.
     */
    else if (
        locationInput.value.trim().length >= 3
    ) {

        searchInput.value =
            locationInput.value.trim();

        setTimeout(
            function () {
                searchLocation(true);
            },
            500
        );

    }


    /*
     * Fix Leaflet map size after rendering.
     */
    setTimeout(
        function () {
            eventMap.invalidateSize();
        },
        300
    );

});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let categoryIndex = 1;

    /*
     * Start item index after the highest existing item index.
     * This prevents duplicate input names.
     */
    let itemIndex = 1;


    /* =========================================================
       FIND EXISTING ITEM INDEX
    ========================================================== */

    document
        .querySelectorAll(
            '#items-container .item-row input[type="text"]'
        )
        .forEach(function (input) {

            const match =
                input.name.match(
                    /items\[(\d+)\]\[title\]/
                );

            if (match) {

                const existingIndex =
                    parseInt(match[1]);

                if (
                    Number.isFinite(existingIndex) &&
                    existingIndex >= itemIndex
                ) {

                    itemIndex =
                        existingIndex + 1;

                }

            }

        });


    /* =========================================================
       BIB CONFIG TOGGLE LOGIC
    ========================================================== */

    const enableBibCheckbox =
        document.getElementById('enable_bib_number');

    const bibConfigWrapper =
        document.getElementById('bib_config_wrapper');

    const shareBibRadios =
        document.querySelectorAll(
            'input[name="share_bib_prefix"]'
        );

    const sharedPrefixBox =
        document.getElementById('shared_prefix_box');


    if (enableBibCheckbox && bibConfigWrapper) {

        enableBibCheckbox.addEventListener(
            'change',
            function () {

                bibConfigWrapper.style.display =
                    this.checked ? 'block' : 'none';

            }
        );

    }


    shareBibRadios.forEach(function (radio) {

        radio.addEventListener(
            'change',
            function () {

                if (this.value === '1') {

                    sharedPrefixBox.style.display =
                        'grid';

                    document
                        .querySelectorAll(
                            '.separate-bib-inputs'
                        )
                        .forEach(function (el) {

                            el.style.display =
                                'none';

                        });

                } else {

                    sharedPrefixBox.style.display =
                        'none';

                    document
                        .querySelectorAll(
                            '.separate-bib-inputs'
                        )
                        .forEach(function (el) {

                            el.style.display =
                                'flex';

                        });

                }

            }
        );

    });


    /* =========================================================
       TICKET CATEGORIES
    ========================================================== */

    const categoryContainer =
        document.getElementById(
            'categories-container'
        );

    const addCategoryBtn =
        document.getElementById(
            'add-category-btn'
        );


    /* ADD TICKET CATEGORY */

    if (addCategoryBtn && categoryContainer) {

        addCategoryBtn.addEventListener(
            'click',
            function () {

                const row =
                    document.createElement('div');


                const selectedBibRadio =
                    document.querySelector(
                        'input[name="share_bib_prefix"]:checked'
                    );


                const separateMode =
                    selectedBibRadio &&
                    selectedBibRadio.value === '0';


                row.className =
                    'category-row grid grid-cols-1 md:grid-cols-6 gap-3';


                row.innerHTML = `
                    <input
                        type="text"
                        name="categories[${categoryIndex}][name]"
                        placeholder="Category (e.g., 21km)"
                        required
                        class="md:col-span-2">

                    <input
                        type="number"
                        step="0.01"
                        name="categories[${categoryIndex}][local_price]"
                        placeholder="Local Price (MMK)"
                        required>

                    <input
                        type="number"
                        step="0.01"
                        name="categories[${categoryIndex}][foreign_price]"
                        placeholder="Foreign Price (Optional)">

                    <input
                        type="number"
                        name="categories[${categoryIndex}][capacity]"
                        min="1"
                        placeholder="Capacity">

                    <div
                        class="flex items-center space-x-2 separate-bib-inputs"
                        style="${separateMode ? 'display:flex;' : 'display:none;'}">

                        <input
                            type="text"
                            name="categories[${categoryIndex}][bib_prefix]"
                            maxlength="3"
                            placeholder="Prefix"
                            class="uppercase">

                        <input
                            type="number"
                            name="categories[${categoryIndex}][bib_start_number]"
                            value="1"
                            min="1"
                            placeholder="Start #">

                        <button
                            type="button"
                            class="remove-category-btn"
                            title="Delete ticket category">

                            <i class="fa-solid fa-trash"></i>

                        </button>

                    </div>
                `;


                categoryContainer.appendChild(row);

                categoryIndex++;

            }
        );


        /* REMOVE TICKET CATEGORY */

        categoryContainer.addEventListener(
            'click',
            function (e) {

                const removeButton =
                    e.target.closest(
                        '.remove-category-btn'
                    );


                if (!removeButton) {
                    return;
                }


                const categoryRow =
                    removeButton.closest(
                        '.category-row'
                    );


                if (categoryRow) {

                    categoryRow.remove();

                }

            }
        );

    }


    /* =========================================================
       EVENT ITEMS
    ========================================================== */

    const itemsContainer =
        document.getElementById(
            'items-container'
        );

    const addItemBtn =
        document.getElementById(
            'add-item-btn'
        );

    const emptyItemsMessage =
        document.getElementById(
            'empty-items-message'
        );


    /*
     * Update empty-state visibility.
     */
    function updateItemsEmptyState() {

        if (!itemsContainer || !emptyItemsMessage) {
            return;
        }


        const itemRows =
            itemsContainer.querySelectorAll(
                '.item-row'
            );


        emptyItemsMessage.style.display =
            itemRows.length === 0
                ? 'block'
                : 'none';

    }


    /*
     * =========================================================
     * ADD EVENT ITEM
     * =========================================================
     */

    if (addItemBtn && itemsContainer) {

        addItemBtn.addEventListener(
            'click',
            function () {

                const currentIndex =
                    itemIndex++;

                const row =
                    document.createElement('div');

                row.className =
                    'item-row';


                row.innerHTML = `
                    <div>

                        <label class="event-label">
                            Item Title
                        </label>

                        <input
                            type="text"
                            name="items[${currentIndex}][title]"
                            placeholder="e.g. Finisher Medal"
                            class="event-input">

                    </div>

                    <div>

                        <label class="event-label">
                            Item Image
                        </label>

                        <input
                            type="file"
                            name="items[${currentIndex}][image]"
                            accept="image/jpeg,image/png,image/jpg,image/gif"
                            class="event-file-input">

                    </div>

                    <button
                        type="button"
                        class="remove-item-btn"
                        title="Delete event item">

                        <i class="fa-solid fa-trash"></i>

                    </button>
                `;


                itemsContainer.appendChild(row);

                updateItemsEmptyState();

            }
        );

    }


    /*
     * =========================================================
     * REMOVE EVENT ITEM
     * =========================================================
     *
     * Event delegation means this works for:
     *
     * - Original item
     * - Dynamically added items
     * - Any number of items
     *
     * The row is actually removed from the DOM, so its
     * items[index][title] and items[index][image] fields are
     * no longer submitted.
     */

    if (itemsContainer) {

        itemsContainer.addEventListener(
            'click',
            function (e) {

                const removeButton =
                    e.target.closest(
                        '.remove-item-btn'
                    );


                if (!removeButton) {
                    return;
                }


                e.preventDefault();
                e.stopPropagation();


                const itemRow =
                    removeButton.closest(
                        '.item-row'
                    );


                if (!itemRow) {
                    return;
                }


                /*
                 * Remove completely.
                 */
                itemRow.remove();


                /*
                 * Update empty state.
                 */
                updateItemsEmptyState();

            }
        );

    }


    /*
     * Initial state.
     */
    updateItemsEmptyState();

});
</script>

@endsection
