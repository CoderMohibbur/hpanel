<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Restrict access to authenticated admin users only.
     */
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'admin']);
    // }

    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::latest()->get();

        return view('admin.dashboard', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|string|min:6|confirmed',
            'is_admin'              => 'nullable|boolean',
            'is_active'             => 'nullable|boolean',
        ]);

        User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'is_admin'          => $request->boolean('is_admin'),
            'is_active'         => $request->boolean('is_active', true),
            'email_verified_at' => now(), // Optional: remove if email verification required
        ]);

        return redirect()->route('admin.users.index')->with('status', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'is_admin'  => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $user->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'is_admin'  => $request->boolean('is_admin'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.users.index')->with('status', 'User updated successfully.');
    }

    /**
     * Remove the specified user (soft delete).
     */
    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('status', 'User deleted successfully.');
    }

    /**
     * Toggle user active/suspended status.
     */
    public function toggleStatus(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'reactivated' : 'suspended';

        return back()->with('status', "User has been {$status}.");
    }
}
