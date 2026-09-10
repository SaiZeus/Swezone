@extends('layouts.admin')

@section('title', 'Add Admin')
@section('page-title', 'Create Admin Account')

@section('content')
<div class="max-w-xl bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
    <form action="{{ route('admin.admins.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-xs font-bold text-slate-700 mb-1">Full Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-indigo-500">
        </div>

        <div class="mb-4">
            <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-indigo-500">
        </div>

        <div class="mb-4">
            <label class="block text-xs font-bold text-slate-700 mb-1">Password</label>
            <input type="password" name="password" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-indigo-500">
        </div>

        <!-- Role Selection -->
        <div class="mb-4">
            <label class="block text-xs font-bold text-slate-700 mb-1">Admin Type</label>
            <select name="role" id="role" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-indigo-500 bg-white" required onchange="toggleEventDropdown(this.value)">
                <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                <option value="event_admin" {{ old('role') === 'event_admin' ? 'selected' : '' }}>Event Organizer</option>
            </select>
        </div>

        <!-- Assign Event Selection (Hidden by default unless event_admin is selected) -->
        <div class="mb-6" id="eventSelectWrapper" style="{{ old('role') === 'event_admin' ? '' : 'display: none;' }}">
            <label class="block text-xs font-bold text-slate-700 mb-1">Assign Event</label>
            <select name="event_id" id="event_id" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-indigo-500 bg-white">
                <option value="">-- Select Event --</option>
                @foreach(\App\Models\Event::all() as $event)
                    <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                        {{ $event->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.admins.index') }}" class="px-4 py-2 bg-slate-100 text-slate-600 text-xs font-bold rounded-xl">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm">Save Admin</button>
        </div>
    </form>
</div>

<script>
function toggleEventDropdown(role) {
    const wrapper = document.getElementById('eventSelectWrapper');
    if (role === 'event_admin') {
        wrapper.style.display = 'block';
    } else {
        wrapper.style.display = 'none';
        document.getElementById('event_id').value = '';
    }
}
</script>
@endsection