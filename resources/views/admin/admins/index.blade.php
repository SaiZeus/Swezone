@extends('layouts.admin')

@section('title', 'Manage Admins')
@section('page-title', 'Admin Accounts')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="text-slate-800 font-bold text-base">System Administrators</h3>
            <p class="text-xs text-slate-400">Manage dashboard users and access permissions.</p>
        </div>
        <a href="{{ route('admin.admins.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition-all flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Add New Admin
        </a>
    </div>

    @if(session('error'))
        <div class="mb-4 p-3 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                    <th class="py-3 px-4">Name</th>
                    <th class="py-3 px-4">Email</th>
                    <th class="py-3 px-4">Created At</th>
                    <th class="py-3 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-600">
                @foreach($admins as $admin)
                <tr class="hover:bg-slate-50/50">
                    <td class="py-3.5 px-4 font-bold text-slate-800 flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 font-extrabold flex items-center justify-center text-[10px]">
                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                        </div>
                        {{ $admin->name }}
                        @if($admin->email === 'admin@gmail.com')
                            <span class="bg-amber-100 text-amber-700 text-[9px] font-extrabold px-2 py-0.5 rounded-md">Super Admin</span>
                        @endif
                    </td>
                    <td class="py-3.5 px-4">{{ $admin->email }}</td>
                    <td class="py-3.5 px-4 text-slate-400">{{ $admin->created_at->format('M d, Y') }}</td>
                    <td class="py-3.5 px-4 text-right space-x-2">
                        <a href="{{ route('admin.admins.edit', $admin->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold px-2 py-1 bg-indigo-50 rounded-lg">Edit</a>
                        @if($admin->email !== 'admin@gmail.com')
                            <form action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this admin?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:text-rose-900 font-bold px-2 py-1 bg-rose-50 rounded-lg">Delete</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection