@extends('layouts.admin')

@section('title', 'Edit Event')
@section('page-title', 'Edit Event - ' . $event->title)

@section('content')

<style>
    .edit-event-page {
        --primary: #4f46e5;
        --primary-dark: #4338ca;
        --text: #172033;
        --muted: #7b8494;
        --border: #e5e7eb;
    }

    /* =========================================
       PAGE HEADER
       ========================================= */

    .edit-event-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .edit-event-heading {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .edit-event-heading-icon {
        width: 48px;
        height: 48px;
        min-width: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: #eef2ff;
        color: #4f46e5;
        font-size: 1.1rem;
    }

    .edit-event-heading h2 {
        margin: 0;
        color: #172033;
        font-size: 1.25rem;
        font-weight: 850;
        letter-spacing: -0.025em;
    }

    .edit-event-heading p {
        margin: 4px 0 0;
        color: #929baa;
        font-size: 0.72rem;
    }

    .back-events-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 13px;
        border: 1px solid #e0e4eb;
        border-radius: 10px;
        background: #ffffff;
        color: #667085;
        font-size: 0.72rem;
        font-weight: 750;
        transition: all .18s ease;
    }

    .back-events-button:hover {
        background: #f8fafc;
        color: #344054;
        border-color: #cfd5df;
        transform: translateX(-2px);
    }

    /* =========================================
       MAIN CARD
       ========================================= */

    .edit-event-card {
        max-width: 920px;
        margin: 0 auto;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        background: #ffffff;
        box-shadow: 0 14px 45px rgba(25, 35, 55, 0.06);
    }

    .edit-event-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 21px 25px;
        border-bottom: 1px solid #eaedf2;
        background: linear-gradient(
            135deg,
            #ffffff 0%,
            #fafbff 100%
        );
    }

    .card-header-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        background: #eef2ff;
        color: #4f46e5;
        font-size: .9rem;
    }

    .card-header-text h3 {
        margin: 0;
        color: #202b3d;
        font-size: .92rem;
        font-weight: 850;
    }

    .card-header-text p {
        margin: 3px 0 0;
        color: #929baa;
        font-size: .67rem;
    }

    .edit-event-form {
        padding: 27px;
    }

    /* =========================================
       SECTION
       ========================================= */

    .form-section {
        margin-bottom: 27px;
    }

    .form-section:last-of-type {
        margin-bottom: 0;
    }

    .form-section-title {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 15px;
    }

    .form-section-title-icon {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #f1f5f9;
        color: #64748b;
        font-size: .65rem;
    }

    .form-section-title span {
        color: #344054;
        font-size: .76rem;
        font-weight: 850;
        letter-spacing: .01em;
    }

    /* =========================================
       FORM FIELDS
       ========================================= */

    .form-field {
        margin-bottom: 17px;
    }

    .form-field:last-child {
        margin-bottom: 0;
    }

    .form-label {
        display: flex;
        align-items: center;
        gap: 4px;
        margin-bottom: 7px;
        color: #475467;
        font-size: .68rem;
        font-weight: 800;
    }

    .required-star {
        color: #ef4444;
    }

    .form-input,
    .form-textarea,
    .form-select {
        width: 100%;
        border: 1px solid #dfe3ea;
        border-radius: 10px;
        background: #ffffff;
        color: #293445;
        font-size: .75rem;
        outline: none;
        transition:
            border-color .18s ease,
            box-shadow .18s ease,
            background .18s ease;
    }

    .form-input,
    .form-select {
        height: 43px;
        padding: 0 12px;
    }

    .form-textarea {
        min-height: 125px;
        padding: 11px 12px;
        resize: vertical;
        line-height: 1.55;
    }

    .form-input::placeholder,
    .form-textarea::placeholder {
        color: #aab1bd;
    }

    .form-input:hover,
    .form-textarea:hover,
    .form-select:hover {
        border-color: #cbd1da;
    }

    .form-input:focus,
    .form-textarea:focus,
    .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, .09);
    }

    /* =========================================
       STATUS SELECT
       ========================================= */

    .status-wrapper {
        position: relative;
    }

    .status-wrapper::after {
        content: "\f107";
        position: absolute;
        right: 13px;
        top: 50%;
        transform: translateY(-50%);
        font-family: "Font Awesome 6 Free";
        font-weight: 900;
        color: #98a2b3;
        pointer-events: none;
    }

    .status-wrapper .form-select {
        appearance: none;
        padding-right: 35px;
    }

    /* =========================================
       IMAGE UPLOAD
       ========================================= */

    .image-upload-box {
        padding: 15px;
        border: 1px dashed #cfd5df;
        border-radius: 12px;
        background: #fafbfc;
        transition: all .18s ease;
    }

    .image-upload-box:hover {
        border-color: #a5b4fc;
        background: #f8f9ff;
    }

    .image-upload-box .form-input {
        border: 0;
        height: auto;
        padding: 0;
        background: transparent;
        font-size: .7rem;
        color: #667085;
    }

    .image-upload-box .form-input:focus {
        box-shadow: none;
    }

    .current-image {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 11px;
        padding: 9px 11px;
        border: 1px solid #e5e7eb;
        border-radius: 9px;
        background: #ffffff;
    }

    .current-image-icon {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #eef2ff;
        color: #6366f1;
        font-size: .65rem;
    }

    .current-image-text {
        flex: 1;
        min-width: 0;
    }

    .current-image-text p {
        margin: 0;
        color: #7b8494;
        font-size: .61rem;
    }

    .view-banner-link {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #4f46e5;
        font-size: .66rem;
        font-weight: 800;
    }

    .view-banner-link:hover {
        color: #4338ca;
        text-decoration: underline;
    }

    /* =========================================
       FORM FOOTER
       ========================================= */

    .edit-event-footer {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 9px;
        margin: 5px -27px -27px;
        padding: 18px 27px;
        border-top: 1px solid #eaedf2;
        background: #fafbfc;
    }

    .cancel-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 41px;
        padding: 0 16px;
        border: 1px solid #dfe3ea;
        border-radius: 9px;
        background: #ffffff;
        color: #667085;
        font-size: .69rem;
        font-weight: 800;
        transition: all .18s ease;
    }

    .cancel-button:hover {
        background: #f8fafc;
        color: #344054;
        border-color: #cbd1da;
    }

    .update-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 41px;
        padding: 0 18px;
        border: 0;
        border-radius: 9px;
        background: linear-gradient(
            135deg,
            #4f46e5 0%,
            #6366f1 100%
        );
        color: #ffffff;
        font-size: .69rem;
        font-weight: 800;
        box-shadow: 0 6px 16px rgba(79, 70, 229, .18);
        transition: all .18s ease;
    }

    .update-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 9px 20px rgba(79, 70, 229, .25);
    }

    /* =========================================
       RESPONSIVE
       ========================================= */

    @media (max-width: 767px) {

        .edit-event-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .back-events-button {
            width: 100%;
            justify-content: center;
        }

        .edit-event-form {
            padding: 20px;
        }

        .edit-event-card-header {
            padding: 18px 20px;
        }

        .edit-event-footer {
            margin: 5px -20px -20px;
            padding: 15px 20px;
            flex-direction: column-reverse;
        }

        .cancel-button,
        .update-button {
            width: 100%;
        }
    }
</style>


<div class="edit-event-page">

    {{-- =========================================
         PAGE HEADER
         ========================================= --}}
    <div class="edit-event-header">

        <div class="edit-event-heading">

            <div class="edit-event-heading-icon">
                <i class="fa-solid fa-pen-to-square"></i>
            </div>

            <div>

                <h2>
                    Edit Marathon Event
                </h2>

                <p>
                    Update the information and settings for this event
                </p>

            </div>

        </div>


        <a
            href="{{ route('admin.events.index') }}"
            class="back-events-button"
        >
            <i class="fa-solid fa-arrow-left"></i>
            Back to Events
        </a>

    </div>


    {{-- =========================================
         MAIN CARD
         ========================================= --}}
    <div class="edit-event-card">

        {{-- Card Header --}}
        <div class="edit-event-card-header">

            <div class="card-header-icon">
                <i class="fa-solid fa-calendar-pen"></i>
            </div>

            <div class="card-header-text">

                <h3>
                    {{ $event->title }}
                </h3>

                <p>
                    Make changes to your marathon event details
                </p>

            </div>

        </div>


        {{-- =====================================
             FORM
             ===================================== --}}
        <form
            action="{{ route('admin.events.update', $event->id) }}"
            method="POST"
            enctype="multipart/form-data"
            class="edit-event-form"
        >

            @csrf
            @method('PUT')


            {{-- =================================
                 BASIC INFORMATION
                 ================================= --}}
            <div class="form-section">

                <div class="form-section-title">

                    <div class="form-section-title-icon">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>

                    <span>
                        Basic Information
                    </span>

                </div>


                {{-- Event Title --}}
                <div class="form-field">

                    <label class="form-label">
                        Event Title
                        <span class="required-star">*</span>
                    </label>

                    <input
                        type="text"
                        name="title"
                        value="{{ old('title', $event->title) }}"
                        required
                        class="form-input"
                        placeholder="Enter event title"
                    >

                </div>


                {{-- Description --}}
                <div class="form-field">

                    <label class="form-label">
                        Description
                        <span class="required-star">*</span>
                    </label>

                    <textarea
                        name="description"
                        rows="4"
                        required
                        class="form-textarea"
                        placeholder="Describe your marathon event..."
                    >{{ old('description', $event->description) }}</textarea>

                </div>

            </div>


            {{-- =================================
                 EVENT DETAILS
                 ================================= --}}
            <div class="form-section">

                <div class="form-section-title">

                    <div class="form-section-title-icon">
                        <i class="fa-solid fa-calendar-days"></i>
                    </div>

                    <span>
                        Event Details
                    </span>

                </div>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Location --}}
                    <div class="form-field">

                        <label class="form-label">
                            Location
                            <span class="required-star">*</span>
                        </label>

                        <input
                            type="text"
                            name="location"
                            value="{{ old('location', $event->location) }}"
                            required
                            class="form-input"
                            placeholder="Event location"
                        >

                    </div>


                    {{-- Date --}}
                    <div class="form-field">

                        <label class="form-label">
                            Event Date & Time
                            <span class="required-star">*</span>
                        </label>

                        <input
                            type="datetime-local"
                            name="event_date"
                            value="{{ old('event_date', \Carbon\Carbon::parse($event->event_date)->format('Y-m-d\TH:i')) }}"
                            required
                            class="form-input"
                        >

                    </div>

                </div>

            </div>


            {{-- =================================
                 STATUS & IMAGE
                 ================================= --}}
            <div class="form-section">

                <div class="form-section-title">

                    <div class="form-section-title-icon">
                        <i class="fa-solid fa-sliders"></i>
                    </div>

                    <span>
                        Event Settings
                    </span>

                </div>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Status --}}
                    <div class="form-field">

                        <label class="form-label">
                            Status
                        </label>

                        <div class="status-wrapper">

                            <select
                                name="status"
                                class="form-select"
                            >

                                <option
                                    value="upcoming"
                                    {{ $event->status === 'upcoming' ? 'selected' : '' }}
                                >
                                    Upcoming
                                </option>

                                <option
                                    value="live"
                                    {{ $event->status === 'live' ? 'selected' : '' }}
                                >
                                    Live
                                </option>

                                <option
                                    value="past"
                                    {{ $event->status === 'past' ? 'selected' : '' }}
                                >
                                    Past
                                </option>

                            </select>

                        </div>

                    </div>


                    {{-- Image --}}
                    <div class="form-field">

                        <label class="form-label">
                            Event Banner Image
                            <span class="text-gray-400 font-normal">
                                (Optional)
                            </span>
                        </label>

                        <div class="image-upload-box">

                            <input
                                type="file"
                                name="image"
                                accept="image/*"
                                class="form-input"
                            >


                            @if($event->image)

                                <div class="current-image">

                                    <div class="current-image-icon">
                                        <i class="fa-solid fa-image"></i>
                                    </div>

                                    <div class="current-image-text">

                                        <p>
                                            Current event banner
                                        </p>

                                    </div>

                                    <a
                                        href="{{ asset('storage/' . $event->image) }}"
                                        target="_blank"
                                        class="view-banner-link"
                                    >
                                        <i class="fa-solid fa-eye"></i>
                                        View Banner
                                    </a>

                                </div>

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================
                 FORM FOOTER
                 ================================= --}}
            <div class="edit-event-footer">

                <a
                    href="{{ route('admin.events.index') }}"
                    class="cancel-button"
                >
                    <i class="fa-solid fa-xmark"></i>
                    Cancel
                </a>


                <button
                    type="submit"
                    class="update-button"
                >
                    <i class="fa-solid fa-check"></i>
                    Update Event
                </button>

            </div>

        </form>

    </div>

</div>

@endsection