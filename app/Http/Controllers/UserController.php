<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display user management page.
     */
    public function index()
    {
        $pendingUsers = User::where('status', 'pending')->orderBy('created_at', 'desc')->get();
        $allUsers = User::where('status', '!=', 'pending')->orderBy('created_at', 'desc')->get();

        return view('admin.user_management', compact('pendingUsers', 'allUsers'));
    }

    /**
     * Store a new user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:admin,teacher,student'],
        ]);

        User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'status' => 'active',
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User added successfully!');
    }

    /**
     * Approve a pending user.
     */
    public function approve(User $user)
    {
        $user->update(['status' => 'active']);

        return redirect()->route('admin.users.index')->with('success', 'User approved successfully!');
    }

    /**
     * Reject a pending user.
     */
    public function reject(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User rejected successfully!');
    }

    /**
     * Update an existing user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'in:admin,teacher,student'],
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }

    /**
     * Delete a user.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
    }
}
