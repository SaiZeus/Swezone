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

    /* =========================================
       PAGE HEADER
       ========================================= */

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

    /* =========================================
       MAIN FORM CARD
       ========================================= */

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

    /* =========================================
       FORM SECTIONS
       ========================================= */

    .form-section {
        margin-bottom: 30px;
    }

    .form-section:last-child {
        margin-bottom: 0;
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

    /* =========================================
       LABELS & INPUTS
       ========================================= */

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
        transition:
            border-color 0.18s ease,
            box-shadow 0.18s ease,
            background 0.18s ease;
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

    /* =========================================
       IMAGE UPLOAD
       ========================================= */

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
        border-radius: 9px;
        background: white;
        color: #7b8493;
        font-size: 0.73rem;
    }

    /* =========================================
       TICKET CATEGORIES
       ========================================= */

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
        transition: all 0.18s ease;
    }

    .category-row:hover {
        border-color: #cfd5e1 !important;
        box-shadow: 0 7px 20px rgba(30, 40, 60, 0.06);
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
        transition: all 0.18s ease;
    }

    .category-row input:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.08);
    }

    .category-row input::placeholder {
        color: #9ca5b3;
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
        transition: all 0.18s ease;
    }

    .remove-category-btn:hover {
        background: #fee2e2;
        color: #dc2626;
        transform: scale(1.03);
    }

    /* =========================================
       PROMO CODE
       ========================================= */

    .promo-box {
        padding: 20px;
        border: 1px solid #e5e8ee;
        border-radius: 16px;
        background: linear-gradient(
            135deg,
            #fff 0%,
            #fafaff 100%
        );
    }

    .promo-heading {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
    }

    .promo-icon {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #fef3c7;
        color: #d97706;
    }

    .promo-heading h3 {
        margin: 0;
        color: #273245;
        font-size: 0.88rem;
        font-weight: 850;
    }

    .promo-heading p {
        margin: 2px 0 0;
        color: #929baa;
        font-size: 0.68rem;
    }

    /* =========================================
       DIVIDERS
       ========================================= */

    .event-divider {
        height: 1px;
        margin: 30px 0;
        border: 0;
        background: #eaedf2;
    }

    /* =========================================
       PUBLISH BUTTON
       ========================================= */

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
        background: linear-gradient(
            135deg,
            #4f46e5 0%,
            #6366f1 100%
        );
        color: white;
        font-size: 0.84rem;
        font-weight: 850;
        letter-spacing: 0.01em;
        box-shadow: 0 9px 22px rgba(79, 70, 229, 0.22);
        transition:
            transform 0.18s ease,
            box-shadow 0.18s ease,
            filter 0.18s ease;
    }

    .publish-button:hover {
        transform: translateY(-2px);
        filter: brightness(1.03);
        box-shadow: 0 13px 28px rgba(79, 70, 229, 0.3);
    }

    .publish-button:active {
        transform: translateY(0);
    }

    /* =========================================
       RESPONSIVE
       ========================================= */

    @media (max-width: 767px) {

        .event-form-body {
            padding: 20px;
        }

        .event-form-header {
            padding: 22px 20px;
        }

        .ticket-builder {
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

        .event-page-header h1 {
            font-size: 1.35rem;
        }
    }
</style>


<div class="create-event-page">

    <div class="event-form-wrapper">

        {{-- =========================================
             PAGE INTRO
             ========================================= --}}
        <div class="event-page-header">
            <h1>Create Marathon Event</h1>
            <p>
                Set up your event details, ticket categories, pricing and promotional offers.
            </p>
        </div>


        {{-- =========================================
             MAIN FORM
             ========================================= --}}
        <div class="event-form-card">

            {{-- Form Header --}}
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

                <form
                    action="{{ route('admin.events.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="space-y-6"
                >

                    @csrf


                    {{-- =========================================
                         SECTION 01 - EVENT INFORMATION
                         ========================================= --}}
                    <div class="form-section">

                        <div class="form-section-heading">

                            <div class="section-number">
                                01
                            </div>

                            <div>
                                <h3>Event Information</h3>
                                <p>Basic details about your marathon event.</p>
                            </div>

                        </div>


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            {{-- Event Title --}}
                            <div>
                                <label class="event-label">
                                    Event Title
                                </label>

                                <input
                                    type="text"
                                    name="title"
                                    required
                                    placeholder="e.g. Yangon International Marathon"
                                    class="event-input"
                                >
                            </div>


                            {{-- Location --}}
                            <div>
                                <label class="event-label">
                                    Location
                                </label>

                                <input
                                    type="text"
                                    name="location"
                                    required
                                    placeholder="e.g. Yangon, Myanmar"
                                    class="event-input"
                                >
                            </div>


                            {{-- Event Date --}}
                            <div>
                                <label class="event-label">
                                    Event Date & Time
                                </label>

                                <input
                                    type="datetime-local"
                                    name="event_date"
                                    required
                                    class="event-input"
                                >
                            </div>


                            {{-- Status --}}
                            <div>
                                <label class="event-label">
                                    Status
                                </label>

                                <select
                                    name="status"
                                    class="event-select"
                                >
                                    <option value="upcoming">
                                        Upcoming
                                    </option>

                                    <option value="live">
                                        Live Now
                                    </option>

                                    <option value="past">
                                        Past
                                    </option>
                                </select>
                            </div>

                        </div>

                    </div>


                    <hr class="event-divider">


                    {{-- =========================================
                         SECTION 02 - DESCRIPTION
                         ========================================= --}}
                    <div class="form-section">

                        <div class="form-section-heading">

                            <div class="section-number">
                                02
                            </div>

                            <div>
                                <h3>Event Description</h3>
                                <p>Tell runners what they need to know about this event.</p>
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
                                class="event-textarea"
                            ></textarea>

                        </div>

                    </div>


                    {{-- =========================================
                         EVENT BANNER
                         ========================================= --}}
                    <div class="form-section">

                        <div class="form-section-heading">

                            <div class="section-number">
                                03
                            </div>

                            <div>
                                <h3>Event Banner</h3>
                                <p>Upload an image to represent your event.</p>
                            </div>

                        </div>


                        <div class="image-upload-box">

                            <div class="image-upload-content">

                                <div class="image-upload-icon">
                                    <i class="fa-solid fa-image"></i>
                                </div>

                                <div class="image-upload-text">

                                    <strong>
                                        Event Banner Image
                                    </strong>

                                    <span>
                                        Choose a high-quality image for your marathon.
                                    </span>

                                </div>

                            </div>

                            <input
                                type="file"
                                name="image"
                                class="event-file-input"
                            >

                        </div>

                    </div>


                    <hr class="event-divider">


                    {{-- =========================================
                         SECTION 04 - TICKET CATEGORIES
                         ========================================= --}}
                    <div class="form-section">

                        <div class="form-section-heading">

                            <div class="section-number">
                                04
                            </div>

                            <div>
                                <h3>Ticket Categories</h3>
                                <p>Create ticket types, prices and participant capacity.</p>
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
                                    class="add-category-button"
                                >
                                    <i class="fa-solid fa-plus"></i>
                                    Add Another Ticket Type
                                </button>

                            </div>


                            <div
                                id="categories-container"
                                class="space-y-3"
                            >

                                {{-- Default Category --}}
                                <div
                                    class="category-row grid grid-cols-1 md:grid-cols-4 gap-3"
                                >

                                    <input
                                        type="text"
                                        name="categories[0][name]"
                                        placeholder="Category (e.g., 10km)"
                                        required
                                    >

                                    <input
                                        type="number"
                                        step="0.01"
                                        name="categories[0][local_price]"
                                        placeholder="Local Price ($)"
                                        required
                                    >

                                    <input
                                        type="number"
                                        step="0.01"
                                        name="categories[0][foreign_price]"
                                        placeholder="Foreign Price (Optional)"
                                    >

                                    <input
                                        type="number"
                                        name="categories[0][capacity]"
                                        placeholder="Capacity (Empty = Unlimited)"
                                    >

                                </div>

                            </div>

                        </div>

                    </div>


                    <hr class="event-divider">


                    {{-- =========================================
                         SECTION 05 - PROMO CODE
                         ========================================= --}}
                    <div class="form-section">

                        <div class="form-section-heading">

                            <div class="section-number">
                                05
                            </div>

                            <div>
                                <h3>Promotion</h3>
                                <p>Optionally create a discount code for this event.</p>
                            </div>

                        </div>


                        <div class="promo-box">

                            <div class="promo-heading">

                                <div class="promo-icon">
                                    <i class="fa-solid fa-tag"></i>
                                </div>

                                <div>
                                    <h3>
                                        Single Event Promo Code
                                    </h3>

                                    <p>
                                        This promotion is optional.
                                    </p>
                                </div>

                            </div>


                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                                {{-- Promo Code --}}
                                <div>

                                    <label class="event-label">
                                        Promo Code
                                    </label>

                                    <input
                                        type="text"
                                        name="promo_code"
                                        placeholder="PROMO CODE (e.g., RUN2026)"
                                        class="event-input uppercase"
                                    >

                                </div>


                                {{-- Promo Type --}}
                                <div>

                                    <label class="event-label">
                                        Discount Type
                                    </label>

                                    <select
                                        name="promo_type"
                                        class="event-select"
                                    >
                                        <option value="fixed">
                                            Fixed Amount Discount ($)
                                        </option>

                                        <option value="percentage">
                                            Percentage Discount (%)
                                        </option>
                                    </select>

                                </div>


                                {{-- Promo Value --}}
                                <div>

                                    <label class="event-label">
                                        Discount Value
                                    </label>

                                    <input
                                        type="number"
                                        step="0.01"
                                        name="promo_value"
                                        placeholder="Discount Value"
                                        class="event-input"
                                    >

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =========================================
                         PUBLISH EVENT
                         ========================================= --}}
                    <div class="pt-2">

                        <button
                            type="submit"
                            class="publish-button"
                        >
                            <i class="fa-solid fa-rocket"></i>
                            Publish Event
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>


{{-- =========================================
     EXISTING JAVASCRIPT
     FUNCTIONALITY UNCHANGED
     ========================================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    let categoryIndex = 1;
    const container = document.getElementById('categories-container');
    const addBtn = document.getElementById('add-category-btn');

    addBtn.addEventListener('click', function () {
        const row = document.createElement('div');
        row.className = 'category-row grid grid-cols-1 md:grid-cols-4 gap-3';
        row.innerHTML = `
            <input type="text" name="categories[${categoryIndex}][name]" placeholder="Category (e.g., 20km)" required>
            <input type="number" step="0.01" name="categories[${categoryIndex}][local_price]" placeholder="Local Price ($)" required>
            <input type="number" step="0.01" name="categories[${categoryIndex}][foreign_price]" placeholder="Foreign Price (Optional)">
            <div class="flex items-center space-x-2">
                <input type="number" name="categories[${categoryIndex}][capacity]" placeholder="Capacity (Empty = Unlimited)" class="w-full">
                <button type="button" class="remove-category-btn">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(row);
        categoryIndex++;
    });

    container.addEventListener('click', function (e) {
        if (e.target.closest('.remove-category-btn')) {
            e.target.closest('.category-row').remove();
        }
    });
});
</script>

@endsection