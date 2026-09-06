@extends('layouts.admin')

@section('title', 'Edit Admin')
@section('page-title', 'Edit Admin Account')

@section('content')
<div class="max-w-xl bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
    <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-xs font-bold text-slate-700 mb-1">Full Name</label>
            <input type="text" name="name" value="{{ old('name', $admin->name) }}" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-indigo-500">
        </div>

        <div class="mb-4">
            <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
            <input type="email" name="email" value="{{ old('email', $admin->email) }}" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-indigo-500">
        </div>

        <div class="mb-6">
            <label class="block text-xs font-bold text-slate-700 mb-1">New Password <span class="text-slate-400 font-normal">(Leave blank to keep current)</span></label>
            <input type="password" name="password" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-indigo-500">
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.admins.index') }}" class="px-4 py-2 bg-slate-100 text-slate-600 text-xs font-bold rounded-xl">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm">Update Admin</button>
        </div>
    </form>
</div>
@endsection