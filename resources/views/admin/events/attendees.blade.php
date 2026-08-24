@extends('layouts.admin')

@section('title', 'Event Attendees & Revenue')
@section('page-title', 'Attendees Directory - ' . $event->title)

@section('content')

<style>
    /* =========================================================
       ATTENDEES DIRECTORY
       UI / VISUAL STYLING ONLY
       ========================================================= */

    .attendees-page {
        --primary: #4f46e5;
        --primary-light: #eef2ff;
        --text: #172033;
        --muted: #7b8798;
        --border: #e6e9ef;
    }

    .attendees-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .attendees-title-wrapper {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .attendees-title-icon {
        width: 48px;
        height: 48px;
        min-width: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: #eef2ff;
        color: #4f46e5;
        font-size: 1.15rem;
    }

    .attendees-title h2 {
        margin: 0;
        color: var(--text);
        font-size: 1.25rem;
        font-weight: 850;
        letter-spacing: -0.025em;
    }

    .attendees-title p {
        margin: 4px 0 0;
        color: var(--muted);
        font-size: 0.75rem;
    }

    .back-events-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 13px;
        border: 1px solid #e0e4eb;
        border-radius: 10px;
        background: #ffffff;
        color: #5f6b7c;
        font-size: 0.72rem;
        font-weight: 750;
        transition: all 0.18s ease;
    }

    .back-events-button:hover {
        border-color: #cfd5df;
        background: #f8fafc;
        color: #374151;
        transform: translateX(-2px);
    }

    .attendee-success-alert {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 22px;
        padding: 13px 16px;
        border: 1px solid #bbf7d0;
        border-radius: 13px;
        background: #f0fdf4;
        color: #166534;
        font-size: 0.78rem;
        font-weight: 650;
    }

    .attendee-success-icon {
        width: 32px;
        height: 32px;
        min-width: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 99px;
        background: #dcfce7;
        color: #16a34a;
    }

    .attendee-stat-card {
        position: relative;
        overflow: hidden;
        padding: 22px;
        border: 1px solid var(--border);
        border-radius: 18px;
        background: #ffffff;
        box-shadow: 0 10px 30px rgba(25, 35, 55, 0.055);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .attendee-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(25, 35, 55, 0.08);
    }

    .attendee-stat-card::after {
        content: "";
        position: absolute;
        width: 110px;
        height: 110px;
        right: -50px;
        bottom: -55px;
        border-radius: 50%;
        opacity: 0.35;
    }

    .stat-revenue { border-color: #d1fae5; }
    .stat-revenue::after { background: #a7f3d0; }
    .stat-runners { border-color: #e0e7ff; }
    .stat-runners::after { background: #c7d2fe; }
    .stat-status { border-color: #dbeafe; }
    .stat-status::after { background: #bfdbfe; }

    .attendee-stat-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .stat-label {
        margin: 0;
        color: #7b8798;
        font-size: 0.65rem;
        font-weight: 850;
        letter-spacing: 0.065em;
        text-transform: uppercase;
    }

    .stat-value {
        margin: 6px 0 0;
        font-size: 1.75rem;
        font-weight: 900;
        letter-spacing: -0.04em;
    }

    .stat-revenue .stat-value { color: #059669; }
    .stat-runners .stat-value { color: #4f46e5; }
    .stat-status .stat-value { color: #2563eb; font-size: 1.2rem; }

    .stat-icon {
        width: 49px;
        height: 49px;
        min-width: 49px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        font-size: 1.1rem;
    }

    .stat-revenue .stat-icon { background: #d1fae5; color: #059669; }
    .stat-runners .stat-icon { background: #e0e7ff; color: #4f46e5; }
    .stat-status .stat-icon { background: #dbeafe; color: #2563eb; }

    .participants-card {
        overflow: hidden;
        margin-top: 28px;
        border: 1px solid var(--border);
        border-radius: 20px;
        background: #ffffff;
        box-shadow: 0 12px 40px rgba(25, 35, 55, 0.055);
    }

    .participants-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 19px 22px;
        border-bottom: 1px solid var(--border);
        background: linear-gradient(135deg, #ffffff 0%, #fafbff 100%);
    }

    .participants-title {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .participants-title-icon {
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #eef2ff;
        color: #4f46e5;
        font-size: 0.8rem;
    }

    .participants-title h3 {
        margin: 0;
        color: #263143;
        font-size: 0.88rem;
        font-weight: 850;
    }

    .participants-title p {
        margin: 2px 0 0;
        color: #929baa;
        font-size: 0.67rem;
    }

    .participant-count {
        padding: 6px 10px;
        border-radius: 999px;
        background: #eef2ff;
        color: #4f46e5;
        font-size: 0.65rem;
        font-weight: 850;
    }

    .participants-table {
        width: 100%;
        border-collapse: collapse;
    }

    .participants-table thead {
        background: #f8f9fc;
    }

    .participants-table thead tr {
        border-bottom: 1px solid var(--border);
    }

    .participants-table th {
        padding: 14px 17px;
        color: #7b8494;
        font-size: 0.64rem;
        font-weight: 850;
        letter-spacing: 0.065em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .participants-table td {
        padding: 16px 17px;
        border-bottom: 1px solid #edf0f4;
        color: #5f6b7c;
        font-size: 0.76rem;
        vertical-align: middle;
    }

    .participants-table tbody tr {
        transition: background 0.18s ease;
    }

    .participants-table tbody tr:hover {
        background: #fafbff;
    }

    .participants-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .runner-code {
        display: inline-flex;
        align-items: center;
        padding: 5px 8px;
        border: 1px solid #e0e7ff;
        border-radius: 7px;
        background: #eef2ff;
        color: #4f46e5;
        font-family: monospace;
        font-size: 0.62rem;
        font-weight: 850;
    }

    .runner-name {
        margin: 7px 0 0;
        color: #1d2939;
        font-size: 0.78rem;
        font-weight: 800;
    }

    .contact-email, .contact-phone, .contact-viber, .contact-emergency {
        display: flex;
        align-items: center;
        gap: 7px;
        color: #344054;
        font-size: 0.7rem;
        font-weight: 700;
    }

    .contact-phone, .contact-viber, .contact-emergency {
        margin-top: 4px;
        color: #64748b;
        font-size: 0.68rem;
    }

    .contact-viber {
        color: #7360f2;
    }

    .document-number {
        padding: 6px 8px;
        border-radius: 7px;
        background: #f8fafc;
        color: #475467;
        font-family: monospace;
        font-size: 0.66rem;
        font-weight: 700;
    }

    .category-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 9px;
        border: 1px solid #e5e7eb;
        border-radius: 999px;
        background: #f8fafc;
        color: #4b5563;
        font-size: 0.65rem;
        font-weight: 750;
    }

    .nationality-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 0.63rem;
        font-weight: 850;
    }

    .nationality-foreigner { background: #eff6ff; color: #2563eb; }
    .nationality-myanmar { background: #ecfdf5; color: #059669; }

    .shirt-size, .blood-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 4px 8px;
        border-radius: 8px;
        background: #f1f5f9;
        color: #344054;
        font-size: 0.68rem;
        font-weight: 850;
    }

    .blood-badge { background: #fef2f2; color: #dc2626; }

    .bib-badge {
        display: inline-block;
        padding: 3px 7px;
        border: 1px dashed #6366f1;
        border-radius: 6px;
        background: #f5f3ff;
        color: #4f46e5;
        font-weight: 800;
        font-size: 0.68rem;
    }

    .attendee-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 6px;
        white-space: nowrap;
    }

    .attendee-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-height: 32px;
        padding: 7px 10px;
        border-radius: 8px;
        font-size: 0.63rem;
        font-weight: 800;
        transition: all 0.18s ease;
    }

    .edit-attendee-button { background: #fffbeb; color: #d97706; }
    .edit-attendee-button:hover { background: #fef3c7; color: #b45309; }
    .delete-attendee-button { background: #fef2f2; color: #dc2626; }
    .delete-attendee-button:hover { background: #fee2e2; color: #b91c1c; }

    .attendees-empty-state { padding: 65px 20px !important; text-align: center; }
    .attendees-empty-icon {
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

    .edit-runner-modal {
        backdrop-filter: blur(5px);
        background: rgba(15, 23, 42, 0.58) !important;
    }

    .edit-runner-modal-card {
        overflow: hidden;
        max-width: 720px !important;
        max-height: 90vh;
        overflow-y: auto;
        border: 1px solid #e5e7eb;
        border-radius: 20px !important;
        background: #ffffff;
        box-shadow: 0 30px 80px rgba(15, 23, 42, 0.2);
    }

    .edit-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 22px;
        border-bottom: 1px solid #eaedf2;
        background: linear-gradient(135deg, #ffffff 0%, #fafbff 100%);
    }

    .edit-modal-title-wrapper { display: flex; align-items: center; gap: 11px; }
    .edit-modal-icon {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        background: #fffbeb;
        color: #d97706;
    }

    .edit-modal-title h3 { margin: 0; color: #202b3d; font-size: 0.92rem; font-weight: 850; }
    .edit-modal-title p { margin: 2px 0 0; color: #929baa; font-size: 0.66rem; }
    .edit-modal-close {
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        color: #9aa3b1;
    }

    .edit-modal-body { padding: 23px; }
    .edit-field { margin-bottom: 15px; }
    .edit-label { display: block; margin-bottom: 7px; color: #475467; font-size: 0.68rem; font-weight: 800; }
    .edit-input, .edit-select, .edit-textarea {
        width: 100%;
        padding: 8px 11px;
        border: 1px solid #dfe3ea;
        border-radius: 9px;
        background: #ffffff;
        color: #293445;
        font-size: 0.74rem;
        outline: none;
    }

    .edit-modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        padding: 17px 23px;
        border-top: 1px solid #eaedf2;
        background: #fafbfc;
    }

    .cancel-edit-button {
        padding: 9px 14px;
        border: 1px solid #dfe3ea;
        border-radius: 9px;
        background: #ffffff;
        color: #667085;
        font-size: 0.68rem;
        font-weight: 800;
    }

    .save-edit-button {
        padding: 9px 15px;
        border: 0;
        border-radius: 9px;
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        color: #ffffff;
        font-size: 0.68rem;
        font-weight: 800;
    }
</style>

<div class="attendees-page">

    {{-- PAGE HEADER --}}
    <div class="attendees-header">
        <div class="attendees-title-wrapper">
            <div class="attendees-title-icon">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="attendees-title">
                <h2>{{ $event->title }}</h2>
                <p>Registered attendees, revenue and detailed runner information</p>
            </div>
        </div>

        <a href="{{ route('admin.events.index') }}" class="back-events-button">
            <i class="fa-solid fa-arrow-left"></i> Back to Events
        </a>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="attendee-success-alert">
            <div class="attendee-success-icon"><i class="fa-solid fa-check"></i></div>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- STATISTICS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        <div class="attendee-stat-card stat-revenue">
            <div class="attendee-stat-content">
                <div>
                    <p class="stat-label">Total Revenue Generated</p>
                    <h3 class="stat-value">${{ number_format($totalRevenue, 2) }}</h3>
                </div>
                <div class="stat-icon"><i class="fa-solid fa-sack-dollar"></i></div>
            </div>
        </div>

        <div class="attendee-stat-card stat-runners">
            <div class="attendee-stat-content">
                <div>
                    <p class="stat-label">Total Confirmed Runners</p>
                    <h3 class="stat-value">{{ $attendees->count() }}</h3>
                </div>
                <div class="stat-icon"><i class="fa-solid fa-person-running"></i></div>
            </div>
        </div>

        <div class="attendee-stat-card stat-status">
            <div class="attendee-stat-content">
                <div>
                    <p class="stat-label">Event Status</p>
                    <h3 class="stat-value capitalize">{{ $event->status }}</h3>
                </div>
                <div class="stat-icon"><i class="fa-solid fa-calendar-check"></i></div>
            </div>
        </div>
    </div>

    {{-- REGISTERED PARTICIPANTS TABLE --}}
    <div class="participants-card">
        <div class="participants-header">
            <div class="participants-title">
                <div class="participants-title-icon"><i class="fa-solid fa-person-running"></i></div>
                <div>
                    <h3>Registered Participants</h3>
                    <p>Manage runners registered for this event</p>
                </div>
            </div>
            <span class="participant-count">{{ $attendees->count() }} Runners</span>
        </div>

        <div class="overflow-x-auto">
            <table class="participants-table">
                <thead>
                    <tr>
                        <th>Reg Code / Runner / Father</th>
                        <th>BIB & Category</th>
                        <th>Contact & Emergency</th>
                        <th>NRC / Passport / Country</th>
                        <th>Demographics</th>
                        <th>Health & ITRA</th>
                        <th>Address & Experience</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendees as $attendee)
                    <tr>
                        {{-- Runner Info --}}
                        <td>
                            <span class="runner-code">{{ $attendee->ticket_code ?? 'REG-' . $attendee->id }}</span>
                            <p class="runner-name">{{ $attendee->full_name }}</p>
                            @if($attendee->father_name)
                                <p class="text-xs text-gray-500 mt-1"><i class="fa-solid fa-user-group me-1"></i> Father: {{ $attendee->father_name }}</p>
                            @endif
                        </td>

                        {{-- BIB & Category --}}
                        <td>
                            @if(!empty($attendee->bib_number))
                                <div class="mb-1">
                                    <span class="bib-badge bg-indigo-50 border-indigo-200 text-indigo-700">
                                        <i class="fa-solid fa-hashtag me-1"></i>BIB: {{ $attendee->bib_number }}
                                    </span>
                                </div>
                            @elseif(!empty($attendee->bib_name))
                                <div class="mb-1">
                                    <span class="bib-badge">
                                        <i class="fa-solid fa-id-badge me-1"></i>{{ $attendee->bib_name }}
                                    </span>
                                </div>
                            @endif

                            <span class="category-badge">
                                <i class="fa-solid fa-ticket"></i>
                                {{ $attendee->ticketCategory->name ?? 'N/A' }}
                            </span>
                        </td>

                        {{-- Contact Info --}}
                        <td>
                            <p class="contact-email"><i class="fa-solid fa-envelope"></i> {{ $attendee->email }}</p>
                            <p class="contact-phone"><i class="fa-solid fa-phone"></i> {{ $attendee->phone }}</p>
                            @if($attendee->viber)
                                <p class="contact-viber"><i class="fa-brands fa-viber"></i> Viber: {{ $attendee->viber }}</p>
                            @endif
                            <p class="contact-emergency text-red-600"><i class="fa-solid fa-phone-volume"></i> ICE: {{ $attendee->emergency_contact ?? 'N/A' }}</p>
                        </td>

                        {{-- NRC / Passport --}}
                        <td>
                            <span class="document-number">{{ $attendee->nrc_passport }}</span>
                            @if($attendee->country)
                                <p class="text-xs text-gray-500 mt-1"><i class="fa-solid fa-globe me-1"></i> {{ $attendee->country }}</p>
                            @endif
                        </td>

                        {{-- Demographics & Shirt --}}
                        <td>
                            <div class="flex flex-col gap-1">
                                <div>
                                    <span class="nationality-badge {{ strtolower($attendee->nationality) === 'foreigner' ? 'nationality-foreigner' : 'nationality-myanmar' }}">
                                        <i class="fa-solid {{ strtolower($attendee->nationality) === 'foreigner' ? 'fa-earth-americas' : 'fa-flag' }}"></i>
                                        {{ $attendee->nationality }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="shirt-size">Size: {{ $attendee->tshirt_size }}</span>
                                    <span class="text-xs text-gray-600 capitalize">{{ $attendee->gender ?? 'N/A' }}</span>
                                </div>
                                @if($attendee->date_of_birth)
                                    <span class="text-xs text-gray-500"><i class="fa-solid fa-cake-candles me-1"></i>{{ \Carbon\Carbon::parse($attendee->date_of_birth)->format('Y-m-d') }}</span>
                                @endif
                            </div>
                        </td>

                        {{-- Health & ITRA --}}
                        <td>
                            <div class="mb-1">
                                <span class="blood-badge"><i class="fa-solid fa-droplet me-1"></i>{{ $attendee->blood_type ?? 'N/A' }}</span>
                            </div>
                            @if(strtolower($attendee->has_medical_condition) === 'yes')
                                <span class="inline-flex items-center text-xs text-red-600 font-bold"><i class="fa-solid fa-triangle-exclamation me-1"></i> Has Condition</span>
                                <p class="text-xs text-gray-500 max-w-xs truncate" title="{{ $attendee->medical_details }}">{{ $attendee->medical_details }}</p>
                            @else
                                <span class="text-xs text-emerald-600 font-medium"><i class="fa-solid fa-circle-check me-1"></i> Clear</span>
                            @endif

                            {{-- ITRA Info --}}
                            @if(strtolower($attendee->itra) === 'yes')
                                <div class="mt-1 pt-1 border-t border-gray-100">
                                    <span class="inline-flex items-center text-xs text-indigo-600 font-bold"><i class="fa-solid fa-award me-1"></i> ITRA Member</span>
                                    @if($attendee->itra_details)
                                        <p class="text-xs text-gray-500 max-w-xs truncate" title="{{ $attendee->itra_details }}">{{ $attendee->itra_details }}</p>
                                    @endif
                                </div>
                            @endif
                        </td>

                        {{-- Address & Experience --}}
                        <td>
                            <p class="text-xs text-gray-700 max-w-xs truncate" title="{{ $attendee->address }}"><i class="fa-solid fa-location-dot me-1 text-gray-400"></i>{{ $attendee->address ?? 'N/A' }}</p>
                            @if($attendee->experience)
                                <p class="text-xs text-gray-500 max-w-xs truncate mt-1" title="{{ $attendee->experience }}"><i class="fa-solid fa-person-running me-1 text-gray-400"></i>{{ $attendee->experience }}</p>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td>
                            <div class="attendee-actions">
                                <button type="button" onclick="document.getElementById('edit-modal-{{ $attendee->id }}').classList.remove('hidden')" class="attendee-action edit-attendee-button">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </button>

                                <form action="{{ route('admin.attendees.destroy', $attendee->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this attendee?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="attendee-action delete-attendee-button">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="attendees-empty-state">
                            <div class="attendees-empty-icon"><i class="fa-solid fa-users-slash"></i></div>
                            <h3>No Runners Registered</h3>
                            <p>No runners registered for this event yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- EDIT RUNNER MODALS --}}
    @foreach($attendees as $attendee)
    <div id="edit-modal-{{ $attendee->id }}" class="edit-runner-modal fixed inset-0 flex items-center justify-center z-50 hidden p-4">
        <div class="edit-runner-modal-card w-full">
            <div class="edit-modal-header">
                <div class="edit-modal-title-wrapper">
                    <div class="edit-modal-icon"><i class="fa-solid fa-user-pen"></i></div>
                    <div class="edit-modal-title">
                        <h3>Edit Runner Details</h3>
                        <p>Update participant profile and event record</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('edit-modal-{{ $attendee->id }}').classList.add('hidden')" class="edit-modal-close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="edit-modal-body">
                <form action="{{ route('admin.attendees.update', $attendee->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="edit-field">
                            <label class="edit-label">Full Name</label>
                            <input type="text" name="full_name" value="{{ $attendee->full_name }}" required class="edit-input">
                        </div>
                        <div class="edit-field">
                            <label class="edit-label">Father Name</label>
                            <input type="text" name="father_name" value="{{ $attendee->father_name }}" class="edit-input">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                        <div class="edit-field">
                            <label class="edit-label">Email</label>
                            <input type="email" name="email" value="{{ $attendee->email }}" required class="edit-input">
                        </div>
                        <div class="edit-field">
                            <label class="edit-label">Phone</label>
                            <input type="text" name="phone" value="{{ $attendee->phone }}" required class="edit-input">
                        </div>
                        <div class="edit-field">
                            <label class="edit-label">Viber Number</label>
                            <input type="text" name="viber" value="{{ $attendee->viber }}" class="edit-input">
                        </div>
                        <div class="edit-field">
                            <label class="edit-label">Emergency Contact (ICE)</label>
                            <input type="text" name="emergency_contact" value="{{ $attendee->emergency_contact }}" required class="edit-input">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="edit-field">
                            <label class="edit-label">Nationality</label>
                            <select name="nationality" class="edit-select">
                                <option value="Myanmar" {{ $attendee->nationality === 'Myanmar' ? 'selected' : '' }}>Myanmar</option>
                                <option value="Foreigner" {{ $attendee->nationality === 'Foreigner' ? 'selected' : '' }}>Foreigner</option>
                            </select>
                        </div>
                        <div class="edit-field">
                            <label class="edit-label">NRC / Passport</label>
                            <input type="text" name="nrc_passport" value="{{ $attendee->nrc_passport }}" required class="edit-input">
                        </div>
                        <div class="edit-field">
                            <label class="edit-label">Country</label>
                            <input type="text" name="country" value="{{ $attendee->country }}" class="edit-input">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-5 gap-4">
                        <div class="edit-field">
                            <label class="edit-label">Gender</label>
                            <select name="gender" class="edit-select">
                                <option value="male" {{ $attendee->gender === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ $attendee->gender === 'female' ? 'selected' : '' }}>Female</option>
                                <option value="prefer_not_to_say" {{ $attendee->gender === 'prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                            </select>
                        </div>
                        <div class="edit-field">
                            <label class="edit-label">Date of Birth</label>
                            <input type="date" name="date_of_birth" value="{{ $attendee->date_of_birth }}" class="edit-input">
                        </div>
                        <div class="edit-field">
                            <label class="edit-label">BIB Number</label>
                            <input type="text" name="bib_number" value="{{ $attendee->bib_number }}" class="edit-input" placeholder="e.g. 1001">
                        </div>
                        <div class="edit-field">
                            <label class="edit-label">BIB Name</label>
                            <input type="text" name="bib_name" maxlength="10" value="{{ $attendee->bib_name }}" class="edit-input">
                        </div>
                        <div class="edit-field">
                            <label class="edit-label">T-Shirt Size</label>
                            <select name="tshirt_size" class="edit-select">
                                @foreach(['S', 'M', 'L', 'XL', '2XL'] as $size)
                                    <option value="{{ $size }}" {{ $attendee->tshirt_size === $size ? 'selected' : '' }}>{{ $size }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="edit-field">
                            <label class="edit-label">Blood Type</label>
                            <select name="blood_type" class="edit-select">
                                @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $type)
                                    <option value="{{ $type }}" {{ $attendee->blood_type === $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="edit-field">
                            <label class="edit-label">Has Medical Condition?</label>
                            <select name="has_medical_condition" class="edit-select">
                                <option value="no" {{ strtolower($attendee->has_medical_condition) !== 'yes' ? 'selected' : '' }}>No</option>
                                <option value="yes" {{ strtolower($attendee->has_medical_condition) === 'yes' ? 'selected' : '' }}>Yes</option>
                            </select>
                        </div>
                        <div class="edit-field">
                            <label class="edit-label">Medical Details</label>
                            <input type="text" name="medical_details" value="{{ $attendee->medical_details }}" class="edit-input">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="edit-field">
                            <label class="edit-label">ITRA Registered?</label>
                            <select name="itra" class="edit-select">
                                <option value="no" {{ strtolower($attendee->itra) !== 'yes' ? 'selected' : '' }}>No</option>
                                <option value="yes" {{ strtolower($attendee->itra) === 'yes' ? 'selected' : '' }}>Yes</option>
                            </select>
                        </div>
                        <div class="edit-field">
                            <label class="edit-label">ITRA Details</label>
                            <input type="text" name="itra_details" value="{{ $attendee->itra_details }}" class="edit-input">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="edit-field">
                            <label class="edit-label">Address</label>
                            <textarea name="address" rows="2" class="edit-textarea">{{ $attendee->address }}</textarea>
                        </div>
                        <div class="edit-field">
                            <label class="edit-label">Running Experience</label>
                            <textarea name="experience" rows="2" class="edit-textarea">{{ $attendee->experience }}</textarea>
                        </div>
                    </div>

                    <div class="edit-modal-footer">
                        <button type="button" onclick="document.getElementById('edit-modal-{{ $attendee->id }}').classList.add('hidden')" class="cancel-edit-button">Cancel</button>
                        <button type="submit" class="save-edit-button"><i class="fa-solid fa-check mr-1"></i> Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

</div>

@endsection