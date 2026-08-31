@extends('layouts.admin')

@section('title', 'Manage Promo Codes - ' . $event->title)
@section('page-title', 'Promo Codes - ' . $event->title)

@section('content')

<style>
    .promo-management-page {
        --primary: #4f46e5;
        --border: #e6e9ef;
        --text: #172033;
        --muted: #7b8798;
    }

    .promo-wrapper {
        max-width: 1100px;
        margin: 0 auto;
    }

    .promo-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .promo-header h1 {
        margin: 0;
        color: var(--text);
        font-size: 1.5rem;
        font-weight: 850;
    }

    .promo-header p {
        margin-top: 4px;
        color: var(--muted);
        font-size: 0.82rem;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 14px;
        border: 1px solid #e0e4eb;
        border-radius: 10px;
        background: #fff;
        color: #475467;
        font-size: 0.74rem;
        font-weight: 750;
        text-decoration: none;
        transition: all 0.18s ease;
    }

    .back-btn:hover {
        background: #f8fafc;
        color: #1d2939;
        border-color: #cfd5df;
    }

    .card {
        border: 1px solid var(--border);
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 10px 30px rgba(25, 35, 55, 0.04);
        margin-bottom: 24px;
        padding: 24px;
    }

    .card-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
        color: var(--text);
        font-size: 0.98rem;
        font-weight: 850;
    }

    .card-title i {
        color: var(--primary);
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        color: #344054;
        font-size: 0.75rem;
        font-weight: 800;
    }

    .form-input, .form-select {
        width: 100%;
        height: 42px;
        padding: 0 12px;
        border: 1px solid #dfe3ea;
        border-radius: 10px;
        font-size: 0.8rem;
        outline: none;
    }

    .form-input:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .submit-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 20px;
        border: 0;
        border-radius: 10px;
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        color: #fff;
        font-size: 0.78rem;
        font-weight: 800;
        cursor: pointer;
        margin-top: 15px;
    }

    .promo-table {
        width: 100%;
        border-collapse: collapse;
    }

    .promo-table th {
        padding: 12px 16px;
        background: #f8f9fc;
        color: #667085;
        font-size: 0.66rem;
        font-weight: 850;
        text-transform: uppercase;
        border-bottom: 1px solid var(--border);
    }

    .promo-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #edf0f4;
        font-size: 0.78rem;
        color: #344054;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 0.65rem;
        font-weight: 800;
    }

    .badge-available {
        background: #ecfdf5;
        color: #059669;
    }

    .badge-used {
        background: #fef2f2;
        color: #dc2626;
    }

    .code-tag {
        font-family: monospace;
        font-weight: 800;
        background: #f1f5f9;
        padding: 4px 8px;
        border-radius: 6px;
        color: #1e293b;
    }

    .scope-box {
        margin-top: 15px;
        padding: 16px;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }

    .scope-options {
        display: flex;
        gap: 20px;
        margin-bottom: 12px;
    }

    .scope-radio {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        font-size: 0.78rem;
        font-weight: 700;
        color: #334155;
    }

    .scope-radio input {
        accent-color: #4f46e5;
    }
</style>

<div class="promo-management-page">
    <div class="promo-wrapper">

        <div class="promo-header">
            <div>
                <h1>Manage Promo Codes</h1>
                <p>Partner company & single-use promo code manager for <strong>{{ $event->title }}</strong></p>
            </div>
            <a href="{{ route('admin.events.index') }}" class="back-btn">
                <i class="fa-solid fa-arrow-left"></i> Back to Events
            </a>
        </div>

        @if(session('success'))
            <div class="p-3 mb-4 text-xs font-bold text-green-800 bg-green-50 border border-green-200 rounded-xl flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-green-600"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- CREATE / BULK GENERATE FORM --}}
        <div class="card">
            <div class="card-title">
                <i class="fa-solid fa-circle-plus"></i> Generate Partner / Event Promo Codes
            </div>

            <form action="{{ route('admin.events.promo_codes.store', $event->id) }}" method="POST">
                @csrf

                <div class="form-grid">
                    <div class="form-group">
                        <label>Partner / Company Name</label>
                        <input type="text" name="company_name" placeholder="e.g. KBZ Bank, Yoma" class="form-input">
                    </div>

                    <div class="form-group">
                        <label>Code or Base Prefix</label>
                        <input type="text" name="code" required placeholder="e.g. KBZ or RUN2026" class="form-input uppercase">
                    </div>

                    <div class="form-group">
                        <label>Discount Type</label>
                        <select name="discount_type" class="form-select">
                            <option value="fixed">Fixed Amount (MMK)</option>
                            <option value="percentage">Percentage (%)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Discount Value</label>
                        <input type="number" step="0.01" min="0" name="discount_value" required placeholder="5000 or 10" class="form-input">
                    </div>

                    <div class="form-group">
                        <label>Quantity to Generate</label>
                        <input type="number" name="promo_quantity" value="1" min="1" max="500" class="form-input">
                    </div>

                    <div class="form-group">
                        <label>Max Uses (If Qty = 1)</label>
                        <input type="number" name="max_uses" value="1" min="1" class="form-input">
                    </div>
                </div>

                {{-- PROMO SCOPE --}}
                <div class="scope-box">
                    <label class="block mb-2 text-xs font-bold text-gray-700">Promo Code Scope</label>
                    
                    <div class="scope-options">
                        <label class="scope-radio">
                            <input type="radio" name="promo_scope" value="event" checked id="scope_event">
                            <span>Entire Event</span>
                        </label>

                        <label class="scope-radio">
                            <input type="radio" name="promo_scope" value="ticket" id="scope_ticket">
                            <span>Specific Ticket Category</span>
                        </label>
                    </div>

                    <div id="ticket_category_wrapper" style="display: none;">
                        <label class="block mb-1 text-xs font-bold text-gray-600">Select Ticket Category</label>
                        <select name="ticket_category_id" class="form-select max-w-md">
                            <option value="">-- Choose Ticket Category --</option>
                            @foreach($event->ticketCategories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fa-solid fa-bolt"></i> Generate Codes
                </button>
            </form>
        </div>

        {{-- PROMO CODES LIST TABLE --}}
        <div class="card p-0 overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                <span class="font-bold text-sm text-gray-800">
                    Existing Promo Codes ({{ $event->promoCodes->count() }})
                </span>
            </div>

            <table class="promo-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Company</th>
                        <th>Applies To</th>
                        <th>Discount</th>
                        <th>Usage</th>
                        <th>Status</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($event->promoCodes as $promo)
                        @php
                            $isUsed = $promo->max_uses > 0 && $promo->uses_count >= $promo->max_uses;
                        @endphp
                        <tr>
                            <td><span class="code-tag">{{ $promo->code }}</span></td>
                            <td>{{ $promo->company_name ?? '-' }}</td>
                            <td>
                                @if($promo->ticket_category_id && $promo->ticketCategory)
                                    <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-md border border-indigo-100">
                                        {{ $promo->ticketCategory->name }}
                                    </span>
                                @else
                                    <span class="text-xs font-bold text-gray-600 bg-gray-100 px-2 py-1 rounded-md">
                                        Entire Event
                                    </span>
                                @endif
                            </td>
                            <td>
                                {{ $promo->discount_type === 'percentage' ? $promo->discount_value . '%' : number_format($promo->discount_value) . ' MMK' }}
                            </td>
                            <td>{{ $promo->uses_count }} / {{ $promo->max_uses }}</td>
                            <td>
                                @if($isUsed)
                                    <span class="badge badge-used">Redeemed / Used</span>
                                @else
                                    <span class="badge badge-available">Available</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <form action="{{ route('admin.promo_codes.destroy', $promo->id) }}" method="POST" onsubmit="return confirm('Delete this code?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-bold">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-400 italic">
                                No promo codes generated for this event yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const scopeEvent = document.getElementById('scope_event');
    const scopeTicket = document.getElementById('scope_ticket');
    const categoryWrapper = document.getElementById('ticket_category_wrapper');

    function toggleCategoryDropdown() {
        if (scopeTicket.checked) {
            categoryWrapper.style.display = 'block';
        } else {
            categoryWrapper.style.display = 'none';
        }
    }

    scopeEvent.addEventListener('change', toggleCategoryDropdown);
    scopeTicket.addEventListener('change', toggleCategoryDropdown);
});
</script>

@endsection