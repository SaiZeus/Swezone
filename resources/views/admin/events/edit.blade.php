@extends('layouts.admin')

@section('title', 'Edit Event')

@section('page-title', 'Edit Event - ' . $event->title)

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

    .back-events-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 13px;
        border: 1px solid #e0e4eb;
        border-radius: 10px;
        background: #fff;
        color: #667085;
        font-size: .72rem;
        font-weight: 750;
        text-decoration: none;
        transition: all .18s ease;
    }

    .back-events-button:hover {
        background: #f8fafc;
        color: #344054;
        border-color: #cfd5df;
        transform: translateX(-2px);
    }

    .event-form-card {
        overflow: hidden;
        background: #ffffff;
        border: 1px solid #e7eaf0;
        border-radius: 22px;
        box-shadow: 0 14px 45px rgba(25, 35, 55, 0.065);
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

    /* SECTIONS */

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
       OPENSTREETMAP + LEAFLET LOCATION PICKER
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

    .location-search-status {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-top: 8px;
        color: #667085;
        font-size: .66rem;
        min-height: 18px;
    }

    .location-search-status i {
        color: #6366f1;
    }

    .location-search-results {
        position: relative;
        z-index: 1000;
        margin-top: 8px;
        border-radius: 11px;
        overflow: hidden;
    }

    .location-result {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        width: 100%;
        padding: 11px 12px;
        border: 1px solid #e5e7eb;
        border-bottom: 0;
        background: #fff;
        color: #344054;
        text-align: left;
        cursor: pointer;
        transition: background .15s ease;
    }

    .location-result:last-child {
        border-bottom: 1px solid #e5e7eb;
    }

    .location-result:hover {
        background: #f5f7ff;
    }

    .location-result-icon {
        width: 28px;
        height: 28px;
        min-width: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #eef2ff;
        color: #4f46e5;
        font-size: .65rem;
    }

    .location-result-text {
        min-width: 0;
    }

    .location-result-title {
        display: block;
        margin-bottom: 2px;
        color: #273245;
        font-size: .71rem;
        font-weight: 800;
    }

    .location-result-address {
        display: block;
        color: #8a94a5;
        font-size: .62rem;
        line-height: 1.4;
    }

    #event-location-map {
        width: 100%;
        height: 350px;
        overflow: hidden;
        border: 1px solid #dfe3ea;
        border-radius: 13px;
        background: #eef1f5;
        z-index: 1;
        margin-top: 10px;
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

    .current-image {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 11px;
        padding: 9px 11px;
        border: 1px solid #e5e7eb;
        border-radius: 9px;
        background: #fff;
    }

    .current-image-icon {
        width: 30px;
        height: 30px;
        min-width: 30px;
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

    .view-file-link {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #4f46e5;
        font-size: .66rem;
        font-weight: 800;
        text-decoration: none;
        white-space: nowrap;
    }

    .view-file-link:hover {
        color: #4338ca;
        text-decoration: underline;
    }

    /* DELETE FILE BUTTON */

    .delete-file-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 7px 9px;
        margin-left: 4px;
        border: 1px solid #fecaca;
        border-radius: 8px;
        background: #fff5f5;
        color: #dc2626;
        font-size: .62rem;
        font-weight: 800;
        cursor: pointer;
        transition: all .16s ease;
        white-space: nowrap;
    }

    .delete-file-button:hover {
        background: #fee2e2;
        border-color: #fca5a5;
    }

    .file-delete-confirmation {
        display: none;
        align-items: center;
        gap: 7px;
        margin-top: 8px;
        padding: 8px 10px;
        border: 1px solid #fecaca;
        border-radius: 8px;
        background: #fef2f2;
        color: #b91c1c;
        font-size: .62rem;
    }

    .file-delete-confirmation.show {
        display: flex;
    }

    .undo-delete-button {
        margin-left: auto;
        padding: 4px 7px;
        border: 1px solid #fca5a5;
        border-radius: 6px;
        background: #fff;
        color: #b91c1c;
        font-size: .6rem;
        font-weight: 800;
        cursor: pointer;
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

    /* WAIVER */

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
        position: relative;
        padding: 13px;
        border: 1px solid #e3e7ed;
        border-radius: 11px;
        background: white;
        transition: all .18s ease;
    }

    .waiver-file.deleted {
        border-color: #fecaca;
        background: #fffafa;
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

    .item-row.marked-for-delete {
        display: none;
    }

    .existing-item-image {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
    }

    .existing-item-image img {
        width: 45px;
        height: 45px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }

    .existing-item-image span {
        color: #98a2b3;
        font-size: .6rem;
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
        border-color: #fca5a5;
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

    /* T-Shirt Sizes Section */
    .tshirt-sizes-wrapper {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .tshirt-sizes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        gap: 10px;
    }

    .tshirt-size-card {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #ffffff;
        cursor: pointer;
        font-size: 0.76rem;
        font-weight: 700;
        color: #374151;
    }

    .tshirt-size-card input[type="checkbox"] {
        accent-color: #4f46e5;
        width: 15px;
        height: 15px;
    }

    .custom-tshirt-input-group {
        display: flex;
        gap: 8px;
        margin-top: 6px;
    }

    .custom-tshirt-input-group input {
        max-width: 250px;
    }

    @media (max-width: 767px) {

        .event-form-body {
            padding: 20px;
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

        .back-events-button {
            width: 100%;
            justify-content: center;
        }

        #event-location-map {
            height: 300px;
        }

        .current-image {
            flex-wrap: wrap;
        }

        .delete-file-button {
            margin-left: 0;
        }
    }

</style>

{{-- LEAFLET CSS --}}
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
>

<div class="create-event-page">

    <div class="event-form-wrapper">

        <div class="event-form-card">

            <div class="event-form-body">

                {{-- SUCCESS MESSAGE --}}

                @if(session('success'))

                    <div class="event-alert event-alert-success">

                        <div class="event-alert-icon">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>

                        <div class="event-alert-content">

                            <strong>Success</strong>

                            <p>
                                {{ session('success') }}
                            </p>

                        </div>

                        <button
                            type="button"
                            class="event-alert-close"
                            onclick="this.parentElement.remove()"
                        >
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

                            <strong>
                                Event Could Not Be Updated
                            </strong>

                            <p>
                                {{ session('error') }}
                            </p>

                        </div>

                        <button
                            type="button"
                            class="event-alert-close"
                            onclick="this.parentElement.remove()"
                        >
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

                            <strong>
                                Please fix the following errors:
                            </strong>

                            <ul class="event-error-list">

                                @foreach($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                        <button
                            type="button"
                            class="event-alert-close"
                            onclick="this.parentElement.remove()"
                        >
                            <i class="fa-solid fa-xmark"></i>
                        </button>

                    </div>

                @endif

                <form
                    action="{{ route('admin.events.update', $event) }}"
                    method="POST"
                    enctype="multipart/form-data"
                    id="edit-event-form"
                >

                    @csrf
                    @method('PUT')

                    {{-- =====================================================
                         DELETION TRACKING
                         ===================================================== --}}

                    <div id="deleted-items-container"></div>

                    <div id="deleted-files-container">
                        <input type="hidden" name="delete_english_waiver" id="delete_english_waiver" value="0">
                        <input type="hidden" name="delete_burmese_waiver" id="delete_burmese_waiver" value="0">
                        <input type="hidden" name="delete_english_consent" id="delete_english_consent" value="0">
                        <input type="hidden" name="delete_burmese_consent" id="delete_burmese_consent" value="0">
                        <input type="hidden" name="delete_english_race_guide" id="delete_english_race_guide" value="0">
                        <input type="hidden" name="delete_burmese_race_guide" id="delete_burmese_race_guide" value="0">
                    </div>

                    {{-- =====================================================
                         SECTION 01 - EVENT INFORMATION
                         ===================================================== --}}

                    <div class="form-section">

                        <div class="form-section-heading">

                            <div class="section-number">
                                01
                            </div>

                            <div>

                                <h3>
                                    Event Information
                                </h3>

                                <p>
                                    Basic details about your marathon event.
                                </p>

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
                                    value="{{ old('title', $event->title) }}"
                                    placeholder="e.g. Yangon International Marathon"
                                    class="event-input"
                                >

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
                                    value="{{ old('location', $event->location) }}"
                                    placeholder="Type a venue, street, city or place..."
                                    autocomplete="off"
                                    class="event-input"
                                >

                                <div class="location-picker">

                                    <div class="location-picker-header">

                                        <div class="location-picker-icon">

                                            <i class="fa-solid fa-location-dot"></i>

                                        </div>

                                        <div>

                                            <h4>
                                                Automatic Event Location
                                            </h4>

                                            <p>
                                                Type the location above and the map will automatically find it.
                                            </p>

                                        </div>

                                    </div>

                                    <div
                                        id="location-search-status"
                                        class="location-search-status"
                                        style="display:none;"
                                    >
                                        <i class="fa-solid fa-spinner fa-spin"></i>

                                        <span>
                                            Searching for location...
                                        </span>
                                    </div>

                                    <div
                                        id="location-search-results"
                                        class="location-search-results"
                                    ></div>

                                    <div id="event-location-map"></div>

                                    <div class="map-instruction">

                                        <i class="fa-solid fa-circle-info"></i>

                                        <span>
                                            Type a place in the Location field. The map is preview-only; you do not need to click or drag anything.
                                        </span>

                                    </div>

                                    <div
                                        id="selected-location-box"
                                        class="selected-location-box"
                                        style="display: none;"
                                    >

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
                                    value="{{ old('latitude', $event->latitude) }}"
                                >

                                <input
                                    type="hidden"
                                    name="longitude"
                                    id="longitude"
                                    value="{{ old('longitude', $event->longitude) }}"
                                >

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
                                    value="{{ old('event_date', \Carbon\Carbon::parse($event->event_date)->format('Y-m-d\TH:i')) }}"
                                    class="event-input"
                                >

                            </div>

                            {{-- STATUS --}}

                            <div>

                                <label class="event-label">
                                    Status
                                </label>

                                <select
                                    name="status"
                                    class="event-select"
                                >

                                    <option
                                        value="upcoming"
                                        {{ old('status', $event->status) === 'upcoming' ? 'selected' : '' }}
                                    >
                                        Upcoming
                                    </option>

                                    <option
                                        value="live"
                                        {{ old('status', $event->status) === 'live' ? 'selected' : '' }}
                                    >
                                        Live Now
                                    </option>

                                    <option
                                        value="past"
                                        {{ old('status', $event->status) === 'past' ? 'selected' : '' }}
                                    >
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
                                    value="{{ old('overall_capacity', $event->overall_capacity) }}"
                                    placeholder="e.g. 5000"
                                    class="event-input"
                                >

                                <div class="field-help">
                                    Optional. This limits the total number of participants across all ticket types. Leave empty for unlimited event capacity.
                                </div>

                            </div>

                        </div>

                    </div>

                    <hr class="event-divider">

                    {{-- =====================================================
                         SECTION 02 - FORM FIELD TOGGLES & BIB CONFIGURATION
                         ===================================================== --}}

                    <div class="form-section">

                        <div class="form-section-heading">

                            <div class="section-number">
                                02
                            </div>

                            <div>

                                <h3>
                                    Form Customization & BIB Setup
                                </h3>

                                <p>
                                    Choose which fields appear on the registration form and configure BIB sequence generation.
                                </p>

                            </div>

                        </div>

                        @php

                            $enabledFields = $event->enabled_fields ?? [
                                'viber',
                                'father_name',
                                'blood_type',
                                'tshirt_size',
                                'has_medical_condition',
                                'itra',
                                'experience',
                                'address'
                            ];

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

                            $selectedTshirtSizes = old('tshirt_sizes', $event->tshirt_sizes ?? ['XS', 'S', 'M', 'L', 'XL', '2XL', '3XL']);
                            $standardSizes = ['XS', 'S', 'M', 'L', 'XL', '2XL', '3XL'];
                            $customSizes = array_diff($selectedTshirtSizes, $standardSizes);

                        @endphp

                        <div class="fields-toggle-box mb-4">

                            <label class="event-label mb-2">
                                Display Fields in Registration Form
                            </label>

                            <div class="toggle-grid">

                                @foreach($availableFields as $fieldKey => $fieldLabel)

                                    <label class="toggle-card">

                                        <input
                                            type="checkbox"
                                            name="enabled_fields[]"
                                            value="{{ $fieldKey }}"
                                            id="field_toggle_{{ $fieldKey }}"
                                            {{ in_array($fieldKey, $enabledFields) ? 'checked' : '' }}
                                        >

                                        <span>
                                            {{ $fieldLabel }}
                                        </span>

                                    </label>

                                @endforeach

                            </div>

                        </div>

                        {{-- T-SHIRT SIZES CONFIGURATION --}}

                        <div 
                            id="tshirt_sizes_config_box" 
                            class="fields-toggle-box mb-4"
                            style="{{ in_array('tshirt_size', $enabledFields) ? 'display: block;' : 'display: none;' }}"
                        >

                            <label class="event-label mb-1">
                                Available T-Shirt Sizes
                            </label>

                            <p class="field-help mb-3 mt-0">
                                Select which shirt sizes participants can choose during registration.
                            </p>

                            <div class="tshirt-sizes-wrapper">

                                <div class="tshirt-sizes-grid" id="tshirt_sizes_grid">

                                    @foreach($standardSizes as $size)

                                        <label class="tshirt-size-card">

                                            <input
                                                type="checkbox"
                                                name="tshirt_sizes[]"
                                                value="{{ $size }}"
                                                {{ in_array($size, $selectedTshirtSizes) ? 'checked' : '' }}
                                            >

                                            <span>{{ $size }}</span>

                                        </label>

                                    @endforeach

                                    @foreach($customSizes as $customSize)

                                        <label class="tshirt-size-card custom-size-card">

                                            <input
                                                type="checkbox"
                                                name="tshirt_sizes[]"
                                                value="{{ $customSize }}"
                                                checked
                                            >

                                            <span>{{ $customSize }}</span>

                                        </label>

                                    @endforeach

                                </div>

                                <div class="custom-tshirt-input-group mt-2">

                                    <input
                                        type="text"
                                        id="custom_tshirt_input"
                                        placeholder="Add custom size (e.g. 4XL, Kids M)"
                                        class="event-input"
                                    >

                                    <button
                                        type="button"
                                        id="add_custom_tshirt_btn"
                                        class="add-category-button"
                                        style="padding: 0 15px; white-space: nowrap;"
                                    >
                                        <i class="fa-solid fa-plus"></i> Add
                                    </button>

                                </div>

                            </div>

                        </div>

                        <div class="fields-toggle-box">

                            <div class="flex items-center gap-3 mb-3">

                                <input
                                    type="checkbox"
                                    name="enable_bib_number"
                                    id="enable_bib_number"
                                    value="1"
                                    {{ $event->enable_bib_number ?? true ? 'checked' : '' }}
                                    class="w-4 h-4 accent-indigo-600"
                                >

                                <label
                                    for="enable_bib_number"
                                    class="event-label mb-0 cursor-pointer"
                                >
                                    Enable Automatic BIB Generation
                                </label>

                            </div>

                            <div
                                id="bib_config_wrapper"
                                class="mt-3 border-t border-gray-200 pt-3"
                                style="{{ ($event->enable_bib_number ?? true) ? 'display:block;' : 'display:none;' }}"
                            >

                                <label class="event-label mb-2">
                                    BIB Prefix Mode
                                </label>

                                <div class="flex gap-4 mb-4">

                                    <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-gray-700">

                                        <input
                                            type="radio"
                                            name="share_bib_prefix"
                                            value="1"
                                            {{ ($event->share_bib_prefix ?? true) ? 'checked' : '' }}
                                            class="accent-indigo-600"
                                        >

                                        Entire Event Shares Same Prefix (e.g. NA-0001)

                                    </label>

                                    <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-gray-700">

                                        <input
                                            type="radio"
                                            name="share_bib_prefix"
                                            value="0"
                                            {{ !($event->share_bib_prefix ?? true) ? 'checked' : '' }}
                                            class="accent-indigo-600"
                                        >

                                        Separate Prefix Per Ticket Category

                                    </label>

                                </div>

                                <div
                                    id="shared_prefix_box"
                                    class="grid grid-cols-1 md:grid-cols-2 gap-3"
                                    style="{{ ($event->share_bib_prefix ?? true) ? 'display:grid;' : 'display:none;' }}"
                                >

                                    <div>

                                        <label class="event-label">
                                            Event BIB Prefix (Max 3 Chars)
                                        </label>

                                        <input
                                            type="text"
                                            name="event_bib_prefix"
                                            maxlength="3"
                                            value="{{ old('event_bib_prefix', $event->event_bib_prefix) }}"
                                            placeholder="e.g. NA"
                                            class="event-input uppercase"
                                        >

                                    </div>

                                    <div>

                                        <label class="event-label">
                                            Start Number
                                        </label>

                                        <input
                                            type="number"
                                            name="event_bib_start_number"
                                            value="{{ old('event_bib_start_number', $event->event_bib_start_number ?? 1) }}"
                                            min="1"
                                            class="event-input"
                                        >

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <hr class="event-divider">

                    {{-- =====================================================
                         SECTION 03 - EVENT CREATOR
                         ===================================================== --}}

                    <div class="form-section">

                        <div class="form-section-heading">

                            <div class="section-number">
                                03
                            </div>

                            <div>

                                <h3>
                                    Event Creator
                                </h3>

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

                                    <h3>
                                        Event Organizer / Creator
                                    </h3>

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
                                        value="{{ old('creator_name', $event->creator_name ?? '') }}"
                                        placeholder="e.g. John Doe"
                                        class="event-input"
                                        required
                                    >

                                </div>

                                <div>

                                    <label class="event-label">
                                        Creator Phone Number
                                    </label>

                                    <input
                                        type="text"
                                        name="creator_phone"
                                        value="{{ old('creator_phone', $event->creator_phone ?? '') }}"
                                        placeholder="e.g. 09 123 456 789"
                                        class="event-input"
                                        required
                                    >

                                </div>

                                <div>

                                    <label class="event-label">
                                        Creator Email
                                    </label>

                                    <input
                                        type="email"
                                        name="creator_email"
                                        value="{{ old('creator_email', $event->creator_email ?? '') }}"
                                        placeholder="e.g. organizer@example.com"
                                        class="event-input"
                                        required
                                    >

                                </div>

                            </div>

                        </div>

                    </div>

                    <hr class="event-divider">

                    {{-- =====================================================
                         SECTION 04 - DESCRIPTION
                         ===================================================== --}}

                    <div class="form-section">

                        <div class="form-section-heading">

                            <div class="section-number">
                                04
                            </div>

                            <div>

                                <h3>
                                    Event Description
                                </h3>

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
                                class="event-textarea"
                            >{{ old('description', $event->description) }}</textarea>

                        </div>

                    </div>

                    <hr class="event-divider">

                    {{-- =====================================================
                         SECTION 05 - EVENT BANNER
                         ===================================================== --}}

                    <div class="form-section">

                        <div class="form-section-heading">

                            <div class="section-number">
                                05
                            </div>

                            <div>

                                <h3>
                                    Event Banner
                                </h3>

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

                                    <strong>
                                        Event Banner Image
                                    </strong>

                                    <span>
                                        Choose a high-quality image for your marathon (Optional when updating).
                                    </span>

                                </div>

                            </div>

                            <input
                                type="file"
                                name="image"
                                accept="image/jpeg,image/png,image/jpg,image/gif"
                                class="event-file-input"
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
                                        class="view-file-link"
                                    >
                                        <i class="fa-solid fa-eye"></i>
                                        View Banner
                                    </a>

                                </div>

                            @endif

                        </div>

                    </div>

                    <hr class="event-divider">

                    {{-- =====================================================
                         SECTION 06 - TICKET CATEGORIES
                         ===================================================== --}}

                    <div class="form-section">

                        <div class="form-section-heading">

                            <div class="section-number">
                                06
                            </div>

                            <div>

                                <h3>
                                    Ticket Categories
                                </h3>

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

                                        <h3>
                                            Ticket Types
                                        </h3>

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

                                @php
                                    $isShared = $event->share_bib_prefix ?? true;
                                @endphp

                                @forelse($event->ticketCategories as $index => $category)

                                    <div class="category-row grid grid-cols-1 md:grid-cols-6 gap-3">

                                        <input
                                            type="hidden"
                                            name="categories[{{ $index }}][id]"
                                            value="{{ $category->id }}"
                                        >

                                        <input
                                            type="text"
                                            name="categories[{{ $index }}][name]"
                                            value="{{ old("categories.$index.name", $category->name) }}"
                                            placeholder="Category (e.g., 10km)"
                                            required
                                            class="md:col-span-2"
                                        >

                                        <input
                                            type="number"
                                            step="0.01"
                                            name="categories[{{ $index }}][local_price]"
                                            value="{{ old("categories.$index.local_price", $category->local_price) }}"
                                            placeholder="Local Price (MMK)"
                                            required
                                        >

                                        <input
                                            type="number"
                                            step="0.01"
                                            name="categories[{{ $index }}][foreign_price]"
                                            value="{{ old("categories.$index.foreign_price", $category->foreign_price) }}"
                                            placeholder="Foreign Price (Optional)"
                                        >

                                        <input
                                            type="number"
                                            name="categories[{{ $index }}][capacity]"
                                            value="{{ old("categories.$index.capacity", $category->capacity) }}"
                                            min="1"
                                            placeholder="Capacity"
                                        >

                                        <div
                                            class="flex items-center space-x-2 separate-bib-inputs"
                                            style="{{ !$isShared ? 'display:flex;' : 'display:none;' }}"
                                        >

                                            <input
                                                type="text"
                                                name="categories[{{ $index }}][bib_prefix]"
                                                value="{{ old("categories.$index.bib_prefix", $category->bib_prefix) }}"
                                                maxlength="3"
                                                placeholder="Prefix"
                                                class="uppercase"
                                            >

                                            <input
                                                type="number"
                                                name="categories[{{ $index }}][bib_start_number]"
                                                value="{{ old("categories.$index.bib_start_number", $category->bib_start_number ?? 1) }}"
                                                min="1"
                                                placeholder="Start #"
                                            >

                                            <button
                                                type="button"
                                                class="remove-category-btn"
                                            >
                                                <i class="fa-solid fa-trash"></i>
                                            </button>

                                        </div>

                                    </div>

                                @empty

                                    <div class="category-row grid grid-cols-1 md:grid-cols-6 gap-3">

                                        <input
                                            type="text"
                                            name="categories[0][name]"
                                            placeholder="Category (e.g., 10km)"
                                            required
                                            class="md:col-span-2"
                                        >

                                        <input
                                            type="number"
                                            step="0.01"
                                            name="categories[0][local_price]"
                                            placeholder="Local Price (MMK)"
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
                                            min="1"
                                            placeholder="Capacity"
                                        >

                                        <div
                                            class="flex items-center space-x-2 separate-bib-inputs"
                                            style="{{ !$isShared ? 'display:flex;' : 'display:none;' }}"
                                        >

                                            <input
                                                type="text"
                                                name="categories[0][bib_prefix]"
                                                maxlength="3"
                                                placeholder="Prefix"
                                                class="uppercase"
                                            >

                                            <input
                                                type="number"
                                                name="categories[0][bib_start_number]"
                                                value="1"
                                                min="1"
                                                placeholder="Start #"
                                            >

                                            <button
                                                type="button"
                                                class="remove-category-btn"
                                            >
                                                <i class="fa-solid fa-trash"></i>
                                            </button>

                                        </div>

                                    </div>

                                @endforelse

                            </div>

                        </div>

                    </div>

                    <hr class="event-divider">

                    {{-- =====================================================
                         SECTION 07 - EVENT ITEMS
                         ===================================================== --}}

                    <div class="form-section">

                        <div class="form-section-heading">

                            <div class="section-number">
                                07
                            </div>

                            <div>

                                <h3>
                                    Event Items
                                </h3>

                                <p>
                                    Add any items or benefits included with the event.
                                </p>

                            </div>

                        </div>

                        <div class="items-builder">

                            <div class="promo-heading">

                                <div
                                    class="promo-icon"
                                    style="background:#ecfdf5;color:#059669;"
                                >
                                    <i class="fa-solid fa-gift"></i>
                                </div>

                                <div>

                                    <h3>
                                        What's Included?
                                    </h3>

                                    <p>
                                        These items are independent from ticket categories.
                                    </p>

                                </div>

                            </div>

                            <div id="items-container">

                                @forelse($event->items as $index => $item)

                                    <div
                                        class="item-row"
                                        data-item-id="{{ $item->id }}"
                                    >

                                        <input
                                            type="hidden"
                                            name="items[{{ $index }}][id]"
                                            value="{{ $item->id }}"
                                        >

                                        <div>

                                            <label class="event-label">
                                                Item Title
                                            </label>

                                            <input
                                                type="text"
                                                name="items[{{ $index }}][title]"
                                                value="{{ old("items.$index.title", $item->title) }}"
                                                placeholder="e.g. Running Shirt"
                                                class="event-input"
                                            >

                                        </div>

                                        <div>

                                            <label class="event-label">
                                                Item Image
                                            </label>

                                            <input
                                                type="file"
                                                name="items[{{ $index }}][image]"
                                                accept="image/jpeg,image/png,image/jpg,image/gif"
                                                class="event-file-input"
                                            >

                                            @if($item->image)

                                                <div class="existing-item-image">

                                                    <img
                                                        src="{{ asset('storage/' . $item->image) }}"
                                                        alt="{{ $item->title }}"
                                                    >

                                                    <span>
                                                        Current image
                                                    </span>

                                                </div>

                                            @endif

                                        </div>

                                        <button
                                            type="button"
                                            class="remove-item-btn"
                                            data-existing-item="true"
                                            title="Delete this event item"
                                        >
                                            <i class="fa-solid fa-trash"></i>
                                        </button>

                                    </div>

                                @empty

                                    <div class="item-row">

                                        <div>

                                            <label class="event-label">
                                                Item Title
                                            </label>

                                            <input
                                                type="text"
                                                name="items[0][title]"
                                                placeholder="e.g. Running Shirt"
                                                class="event-input"
                                            >

                                        </div>

                                        <div>

                                            <label class="event-label">
                                                Item Image
                                            </label>

                                            <input
                                                type="file"
                                                name="items[0][image]"
                                                accept="image/jpeg,image/png,image/jpg,image/gif"
                                                class="event-file-input"
                                            >

                                        </div>

                                        <button
                                            type="button"
                                            class="remove-item-btn"
                                            data-existing-item="false"
                                            title="Remove item"
                                        >
                                            <i class="fa-solid fa-trash"></i>
                                        </button>

                                    </div>

                                @endforelse

                            </div>

                            <button
                                type="button"
                                id="add-item-btn"
                                class="add-item-button"
                            >
                                <i class="fa-solid fa-plus"></i>
                                Add Another Item
                            </button>

                        </div>

                    </div>

                    <hr class="event-divider">

                    {{-- =====================================================
                         SECTION 08 - WAIVERS, CONSENTS & RACE GUIDES
                         ===================================================== --}}

                    <div class="form-section">

                        <div class="form-section-heading">

                            <div class="section-number">
                                08
                            </div>

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

                                <div
                                    class="waiver-file"
                                    data-file-container="english_waiver"
                                >

                                    <label class="event-label">
                                        English Waiver PDF
                                    </label>

                                    <input
                                        type="file"
                                        name="english_waiver"
                                        accept="application/pdf"
                                        class="event-file-input"
                                    >

                                    <div class="field-help">
                                        Optional PDF document.
                                    </div>

                                    @if(!empty($event->english_waiver))

                                        <div class="current-image">

                                            <div class="current-image-icon">
                                                <i class="fa-solid fa-file-pdf"></i>
                                            </div>

                                            <div class="current-image-text">

                                                <p>
                                                    Current English Waiver
                                                </p>

                                            </div>

                                            <a
                                                href="{{ asset('storage/' . $event->english_waiver) }}"
                                                target="_blank"
                                                class="view-file-link"
                                            >
                                                <i class="fa-solid fa-eye"></i>
                                                View PDF
                                            </a>

                                            <button
                                                type="button"
                                                class="delete-file-button"
                                                data-delete-file="english_waiver"
                                            >
                                                <i class="fa-solid fa-trash"></i>
                                                Delete
                                            </button>

                                        </div>

                                        <div
                                            class="file-delete-confirmation"
                                            data-delete-message="english_waiver"
                                        >

                                            <i class="fa-solid fa-triangle-exclamation"></i>

                                            <span>
                                                This PDF will be deleted when you update the event.
                                            </span>

                                            <button
                                                type="button"
                                                class="undo-delete-button"
                                                data-undo-file="english_waiver"
                                            >
                                                Undo
                                            </button>

                                        </div>

                                    @endif

                                </div>

                                {{-- BURMESE WAIVER --}}

                                <div
                                    class="waiver-file"
                                    data-file-container="burmese_waiver"
                                >

                                    <label class="event-label">
                                        Burmese Waiver PDF
                                    </label>

                                    <input
                                        type="file"
                                        name="burmese_waiver"
                                        accept="application/pdf"
                                        class="event-file-input"
                                    >

                                    <div class="field-help">
                                        Optional PDF document.
                                    </div>

                                    @if(!empty($event->burmese_waiver))

                                        <div class="current-image">

                                            <div class="current-image-icon">
                                                <i class="fa-solid fa-file-pdf"></i>
                                            </div>

                                            <div class="current-image-text">

                                                <p>
                                                    Current Burmese Waiver
                                                </p>

                                            </div>

                                            <a
                                                href="{{ asset('storage/' . $event->burmese_waiver) }}"
                                                target="_blank"
                                                class="view-file-link"
                                            >
                                                <i class="fa-solid fa-eye"></i>
                                                View PDF
                                            </a>

                                            <button
                                                type="button"
                                                class="delete-file-button"
                                                data-delete-file="burmese_waiver"
                                            >
                                                <i class="fa-solid fa-trash"></i>
                                                Delete
                                            </button>

                                        </div>

                                        <div
                                            class="file-delete-confirmation"
                                            data-delete-message="burmese_waiver"
                                        >

                                            <i class="fa-solid fa-triangle-exclamation"></i>

                                            <span>
                                                This PDF will be deleted when you update the event.
                                            </span>

                                            <button
                                                type="button"
                                                class="undo-delete-button"
                                                data-undo-file="burmese_waiver"
                                            >
                                                Undo
                                            </button>

                                        </div>

                                    @endif

                                </div>

                                {{-- ENGLISH CONSENT FORM --}}

                                <div
                                    class="waiver-file"
                                    data-file-container="english_consent"
                                >

                                    <label class="event-label">
                                        English Consent Form PDF (Optional)
                                    </label>

                                    <input
                                        type="file"
                                        name="english_consent"
                                        accept="application/pdf"
                                        class="event-file-input"
                                    >

                                    <div class="field-help">
                                        Optional downloadable consent form. Skipped if left empty.
                                    </div>

                                    @if(!empty($event->english_consent))

                                        <div class="current-image">

                                            <div class="current-image-icon">
                                                <i class="fa-solid fa-file-pdf"></i>
                                            </div>

                                            <div class="current-image-text">

                                                <p>
                                                    Current English Consent
                                                </p>

                                            </div>

                                            <a
                                                href="{{ asset('storage/' . $event->english_consent) }}"
                                                target="_blank"
                                                class="view-file-link"
                                            >
                                                <i class="fa-solid fa-eye"></i>
                                                View PDF
                                            </a>

                                            <button
                                                type="button"
                                                class="delete-file-button"
                                                data-delete-file="english_consent"
                                            >
                                                <i class="fa-solid fa-trash"></i>
                                                Delete
                                            </button>

                                        </div>

                                        <div
                                            class="file-delete-confirmation"
                                            data-delete-message="english_consent"
                                        >

                                            <i class="fa-solid fa-triangle-exclamation"></i>

                                            <span>
                                                This PDF will be deleted when you update the event.
                                            </span>

                                            <button
                                                type="button"
                                                class="undo-delete-button"
                                                data-undo-file="english_consent"
                                            >
                                                Undo
                                            </button>

                                        </div>

                                    @endif

                                </div>

                                {{-- BURMESE CONSENT FORM --}}

                                <div
                                    class="waiver-file"
                                    data-file-container="burmese_consent"
                                >

                                    <label class="event-label">
                                        Burmese Consent Form PDF (Optional)
                                    </label>

                                    <input
                                        type="file"
                                        name="burmese_consent"
                                        accept="application/pdf"
                                        class="event-file-input"
                                    >

                                    <div class="field-help">
                                        Optional downloadable consent form. Skipped if left empty.
                                    </div>

                                    @if(!empty($event->burmese_consent))

                                        <div class="current-image">

                                            <div class="current-image-icon">
                                                <i class="fa-solid fa-file-pdf"></i>
                                            </div>

                                            <div class="current-image-text">

                                                <p>
                                                    Current Burmese Consent
                                                </p>

                                            </div>

                                            <a
                                                href="{{ asset('storage/' . $event->burmese_consent) }}"
                                                target="_blank"
                                                class="view-file-link"
                                            >
                                                <i class="fa-solid fa-eye"></i>
                                                View PDF
                                            </a>

                                            <button
                                                type="button"
                                                class="delete-file-button"
                                                data-delete-file="burmese_consent"
                                            >
                                                <i class="fa-solid fa-trash"></i>
                                                Delete
                                            </button>

                                        </div>

                                        <div
                                            class="file-delete-confirmation"
                                            data-delete-message="burmese_consent"
                                        >

                                            <i class="fa-solid fa-triangle-exclamation"></i>

                                            <span>
                                                This PDF will be deleted when you update the event.
                                            </span>

                                            <button
                                                type="button"
                                                class="undo-delete-button"
                                                data-undo-file="burmese_consent"
                                            >
                                                Undo
                                            </button>

                                        </div>

                                    @endif

                                </div>

                                {{-- ENGLISH RACE GUIDE --}}

                                <div
                                    class="waiver-file"
                                    data-file-container="english_race_guide"
                                >

                                    <label class="event-label">
                                        English Race Guide PDF (Optional)
                                    </label>

                                    <input
                                        type="file"
                                        name="english_race_guide"
                                        accept="application/pdf"
                                        class="event-file-input"
                                    >

                                    <div class="field-help">
                                        Optional PDF document for race instructions.
                                    </div>

                                    @if(!empty($event->english_race_guide))

                                        <div class="current-image">

                                            <div class="current-image-icon">
                                                <i class="fa-solid fa-file-pdf"></i>
                                            </div>

                                            <div class="current-image-text">

                                                <p>
                                                    Current English Race Guide
                                                </p>

                                            </div>

                                            <a
                                                href="{{ asset('storage/' . $event->english_race_guide) }}"
                                                target="_blank"
                                                class="view-file-link"
                                            >
                                                <i class="fa-solid fa-eye"></i>
                                                View File
                                            </a>

                                            <button
                                                type="button"
                                                class="delete-file-button"
                                                data-delete-file="english_race_guide"
                                            >
                                                <i class="fa-solid fa-trash"></i>
                                                Delete
                                            </button>

                                        </div>

                                        <div
                                            class="file-delete-confirmation"
                                            data-delete-message="english_race_guide"
                                        >

                                            <i class="fa-solid fa-triangle-exclamation"></i>

                                            <span>
                                                This PDF will be deleted when you update the event.
                                            </span>

                                            <button
                                                type="button"
                                                class="undo-delete-button"
                                                data-undo-file="english_race_guide"
                                            >
                                                Undo
                                            </button>

                                        </div>

                                    @endif

                                </div>

                                {{-- BURMESE RACE GUIDE --}}

                                <div
                                    class="waiver-file"
                                    data-file-container="burmese_race_guide"
                                >

                                    <label class="event-label">
                                        Burmese Race Guide PDF (Optional)
                                    </label>

                                    <input
                                        type="file"
                                        name="burmese_race_guide"
                                        accept="application/pdf"
                                        class="event-file-input"
                                    >

                                    <div class="field-help">
                                        Optional PDF document for race instructions.
                                    </div>

                                    @if(!empty($event->burmese_race_guide))

                                        <div class="current-image">

                                            <div class="current-image-icon">
                                                <i class="fa-solid fa-file-pdf"></i>
                                            </div>

                                            <div class="current-image-text">

                                                <p>
                                                    Current Burmese Race Guide
                                                </p>

                                            </div>

                                            <a
                                                href="{{ asset('storage/' . $event->burmese_race_guide) }}"
                                                target="_blank"
                                                class="view-file-link"
                                            >
                                                <i class="fa-solid fa-eye"></i>
                                                View File
                                            </a>

                                            <button
                                                type="button"
                                                class="delete-file-button"
                                                data-delete-file="burmese_race_guide"
                                            >
                                                <i class="fa-solid fa-trash"></i>
                                                Delete
                                            </button>

                                        </div>

                                        <div
                                            class="file-delete-confirmation"
                                            data-delete-message="burmese_race_guide"
                                        >

                                            <i class="fa-solid fa-triangle-exclamation"></i>

                                            <span>
                                                This PDF will be deleted when you update the event.
                                            </span>

                                            <button
                                                type="button"
                                                class="undo-delete-button"
                                                data-undo-file="burmese_race_guide"
                                            >
                                                Undo
                                            </button>

                                        </div>

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="mt-8">
                        <button
                            type="submit"
                            class="publish-button"
                        >
                            <i class="fa-solid fa-floppy-disk"></i>
                            Update Event
                        </button>
                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

{{-- LEAFLET JS --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Leaflet Map & Location Picker ---
        const initialLat = parseFloat(document.getElementById('latitude').value) || 16.8661;
        const initialLng = parseFloat(document.getElementById('longitude').value) || 96.1951;
        
        const map = L.map('event-location-map').setView([initialLat, initialLng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        let marker = L.marker([initialLat, initialLng]).addTo(map);

        const locationInput = document.getElementById('location');
        const statusBox = document.getElementById('location-search-status');
        const resultsBox = document.getElementById('location-search-results');
        const selectedBox = document.getElementById('selected-location-box');
        const selectedText = document.getElementById('selected-location-text');
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');

        if (latInput.value && lngInput.value) {
            selectedBox.style.display = 'flex';
            selectedText.textContent = locationInput.value;
        }

        let searchTimeout;
        locationInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            if (query.length < 3) {
                resultsBox.innerHTML = '';
                statusBox.style.display = 'none';
                return;
            }

            statusBox.style.display = 'flex';
            searchTimeout = setTimeout(() => {
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        statusBox.style.display = 'none';
                        resultsBox.innerHTML = '';
                        if (data && data.length > 0) {
                            data.slice(0, 5).forEach(place => {
                                const btn = document.createElement('button');
                                btn.type = 'button';
                                btn.className = 'location-result';
                                btn.innerHTML = `
                                    <div class="location-result-icon"><i class="fa-solid fa-location-dot"></i></div>
                                    <div class="location-result-text">
                                        <span class="location-result-title">${place.name || place.display_name.split(',')[0]}</span>
                                        <span class="location-result-address">${place.display_name}</span>
                                    </div>
                                `;
                                btn.addEventListener('click', () => {
                                    locationInput.value = place.display_name;
                                    latInput.value = place.lat;
                                    lngInput.value = place.lon;

                                    const lat = parseFloat(place.lat);
                                    const lon = parseFloat(place.lon);
                                    map.setView([lat, lon], 15);
                                    marker.setLatLng([lat, lon]);

                                    selectedText.textContent = place.display_name;
                                    selectedBox.style.display = 'flex';
                                    resultsBox.innerHTML = '';
                                });
                                resultsBox.appendChild(btn);
                            });
                        }
                    })
                    .catch(() => {
                        statusBox.style.display = 'none';
                    });
            }, 400);
        });

        // --- T-Shirt Size Toggle Logic ---
        const tshirtCheckbox = document.getElementById('field_toggle_tshirt_size');
        const tshirtConfigBox = document.getElementById('tshirt_sizes_config_box');
        
        if (tshirtCheckbox) {
            tshirtCheckbox.addEventListener('change', function() {
                tshirtConfigBox.style.display = this.checked ? 'block' : 'none';
            });
        }

        // Add Custom T-Shirt Size
        const addCustomBtn = document.getElementById('add_custom_tshirt_btn');
        const customInput = document.getElementById('custom_tshirt_input');
        const tshirtGrid = document.getElementById('tshirt_sizes_grid');

        if (addCustomBtn && customInput && tshirtGrid) {
            addCustomBtn.addEventListener('click', function() {
                const value = customInput.value.trim().toUpperCase();
                if (!value) return;

                const existingInputs = tshirtGrid.querySelectorAll('input[type="checkbox"]');
                for (let input of existingInputs) {
                    if (input.value === value) {
                        input.checked = true;
                        customInput.value = '';
                        return;
                    }
                }

                const label = document.createElement('label');
                label.className = 'tshirt-size-card custom-size-card';
                label.innerHTML = `
                    <input type="checkbox" name="tshirt_sizes[]" value="${value}" checked>
                    <span>${value}</span>
                `;
                tshirtGrid.appendChild(label);
                customInput.value = '';
            });

            customInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    addCustomBtn.click();
                }
            });
        }

        // --- PDF Deletion Handlers ---
        document.querySelectorAll('.delete-file-button').forEach(button => {
            button.addEventListener('click', function() {
                const fileType = this.getAttribute('data-delete-file');
                const container = document.querySelector(`[data-file-container="${fileType}"]`);
                const confirmationBox = container.querySelector(`[data-delete-message="${fileType}"]`);
                const hiddenInput = document.getElementById(`delete_${fileType}`);

                if (hiddenInput) {
                    hiddenInput.value = '1';
                }
                container.classList.add('deleted');
                this.closest('.current-image').style.display = 'none';
                if (confirmationBox) {
                    confirmationBox.classList.add('show');
                }
            });
        });

        document.querySelectorAll('.undo-delete-button').forEach(button => {
            button.addEventListener('click', function() {
                const fileType = this.getAttribute('data-undo-file');
                const container = document.querySelector(`[data-file-container="${fileType}"]`);
                const confirmationBox = container.querySelector(`[data-delete-message="${fileType}"]`);
                const hiddenInput = document.getElementById(`delete_${fileType}`);
                const currentImageDiv = container.querySelector('.current-image');

                if (hiddenInput) {
                    hiddenInput.value = '0';
                }
                container.classList.remove('deleted');
                if (currentImageDiv) {
                    currentImageDiv.style.display = 'flex';
                }
                if (confirmationBox) {
                    confirmationBox.classList.remove('show');
                }
            });
        });

        // --- Ticket Categories Builder ---
        const addCategoryBtn = document.getElementById('add-category-btn');
        const categoriesContainer = document.getElementById('categories-container');
        let categoryIndex = categoriesContainer.querySelectorAll('.category-row').length;

        addCategoryBtn.addEventListener('click', function() {
            const isShared = document.querySelector('input[name="share_bib_prefix"]:checked').value === '1';
            const row = document.createElement('div');
            row.className = 'category-row grid grid-cols-1 md:grid-cols-6 gap-3';
            row.innerHTML = `
                <input type="text" name="categories[${categoryIndex}][name]" placeholder="Category (e.g., 10km)" required class="md:col-span-2">
                <input type="number" step="0.01" name="categories[${categoryIndex}][local_price]" placeholder="Local Price (MMK)" required>
                <input type="number" step="0.01" name="categories[${categoryIndex}][foreign_price]" placeholder="Foreign Price (Optional)">
                <input type="number" name="categories[${categoryIndex}][capacity]" min="1" placeholder="Capacity">
                <div class="flex items-center space-x-2 separate-bib-inputs" style="${!isShared ? 'display:flex;' : 'display:none;'}">
                    <input type="text" name="categories[${categoryIndex}][bib_prefix]" maxlength="3" placeholder="Prefix" class="uppercase">
                    <input type="number" name="categories[${categoryIndex}][bib_start_number]" value="1" min="1" placeholder="Start #">
                    <button type="button" class="remove-category-btn"><i class="fa-solid fa-trash"></i></button>
                </div>
            `;
            categoriesContainer.appendChild(row);
            categoryIndex++;
        });

        categoriesContainer.addEventListener('click', function(e) {
            const removeBtn = e.target.closest('.remove-category-btn');
            if (removeBtn) {
                const row = removeBtn.closest('.category-row');
                if (categoriesContainer.querySelectorAll('.category-row').length > 1) {
                    row.remove();
                } else {
                    alert('You must have at least one ticket category.');
                }
            }
        });

        // --- Event Items Builder ---
        const addItemBtn = document.getElementById('add-item-btn');
        const itemsContainer = document.getElementById('items-container');
        const deletedItemsContainer = document.getElementById('deleted-items-container');
        let itemIndex = itemsContainer.querySelectorAll('.item-row').length;

        addItemBtn.addEventListener('click', function() {
            const row = document.createElement('div');
            row.className = 'item-row';
            row.innerHTML = `
                <div>
                    <label class="event-label">Item Title</label>
                    <input type="text" name="items[${itemIndex}][title]" placeholder="e.g. Running Shirt" class="event-input">
                </div>
                <div>
                    <label class="event-label">Item Image</label>
                    <input type="file" name="items[${itemIndex}][image]" accept="image/jpeg,image/png,image/jpg,image/gif" class="event-file-input">
                </div>
                <button type="button" class="remove-item-btn" data-existing-item="false" title="Remove item">
                    <i class="fa-solid fa-trash"></i>
                </button>
            `;
            itemsContainer.appendChild(row);
            itemIndex++;
        });

        itemsContainer.addEventListener('click', function(e) {
            const removeBtn = e.target.closest('.remove-item-btn');
            if (removeBtn) {
                const row = removeBtn.closest('.item-row');
                const isExisting = removeBtn.getAttribute('data-existing-item') === 'true';
                const itemId = row.getAttribute('data-item-id');

                if (isExisting && itemId) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'deleted_items[]';
                    input.value = itemId;
                    deletedItemsContainer.appendChild(input);
                }

                if (itemsContainer.querySelectorAll('.item-row').length > 1) {
                    row.remove();
                } else {
                    row.querySelector('input[type="text"]').value = '';
                    const fileInput = row.querySelector('input[type="file"]');
                    if (fileInput) fileInput.value = '';
                    const imgDiv = row.querySelector('.existing-item-image');
                    if (imgDiv) imgDiv.remove();
                }
            }
        });

        // --- BIB Prefix Mode toggles ---
        const enableBibCheckbox = document.getElementById('enable_bib_number');
        const bibConfigWrapper = document.getElementById('bib_config_wrapper');
        if (enableBibCheckbox) {
            enableBibCheckbox.addEventListener('change', function() {
                bibConfigWrapper.style.display = this.checked ? 'block' : 'none';
            });
        }

        const sharePrefixRadios = document.querySelectorAll('input[name="share_bib_prefix"]');
        const sharedPrefixBox = document.getElementById('shared_prefix_box');
        
        sharePrefixRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                const isShared = this.value === '1';
                if (sharedPrefixBox) sharedPrefixBox.style.display = isShared ? 'grid' : 'none';
                
                document.querySelectorAll('.separate-bib-inputs').forEach(el => {
                    el.style.display = isShared ? 'none' : 'flex';
                });
            });
        });
    });
</script>

@endsection