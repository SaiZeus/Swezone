<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminManagementController extends Controller
{
    public function index()
    {
        $admins = Admin::with('event')->latest()->get();
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:admins,email',
            'password' => 'required|min:6',
            'role'     => 'required|in:super_admin,event_admin',
            'event_id' => 'nullable|required_if:role,event_admin|exists:events,id',
        ]);

        Admin::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'event_id' => $request->role === 'event_admin' ? $request->event_id : null,
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Admin account created successfully.');
    }

    public function edit(Admin $admin)
    {
        return view('admin.admins.edit', compact('admin'));
    }

    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', Rule::unique('admins')->ignore($admin->id)],
            'password' => 'nullable|min:6',
            'role'     => 'required|in:super_admin,event_admin',
            'event_id' => 'nullable|required_if:role,event_admin|exists:events,id',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->role = $request->role;
        $admin->event_id = $request->role === 'event_admin' ? $request->event_id : null;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return redirect()->route('admin.admins.index')->with('success', 'Admin account updated successfully.');
    }

    public function destroy(Admin $admin)
    {
        if ($admin->email === 'admin@gmail.com') {
            return redirect()->route('admin.admins.index')->with('error', 'The original super admin account cannot be deleted.');
        }

        if (auth()->id() === $admin->id) {
            return redirect()->route('admin.admins.index')->with('error', 'You cannot delete your own active account.');
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')->with('success', 'Admin account deleted successfully.');
    }
}