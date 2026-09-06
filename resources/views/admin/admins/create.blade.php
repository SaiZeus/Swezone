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

        <div class="mb-6">
            <label class="block text-xs font-bold text-slate-700 mb-1">Password</label>
            <input type="password" name="password" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-indigo-500">
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.admins.index') }}" class="px-4 py-2 bg-slate-100 text-slate-600 text-xs font-bold rounded-xl">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm">Save Admin</button>
        </div>
    </form>
</div>
@endsection