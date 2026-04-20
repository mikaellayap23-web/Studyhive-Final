<?php

namespace App\Http\Controllers;

use App\Mail\AccountApproved;
use App\Mail\AccountCreated;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display user management page.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Apply filters
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $pendingUsers = (clone $query)->where('status', 'pending')->orderBy('created_at', 'desc')->paginate(15, ['*'], 'pending_page');
        $allUsers = (clone $query)->where('status', '!=', 'pending')->orderBy('created_at', 'desc')->paginate(15, ['*'], 'users_page');

        return view('admin.user_management', compact('pendingUsers', 'allUsers'));
    }

    /**
     * Store a new user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'in:admin,teacher,student'],
        ], [
            'first_name.regex' => 'The first name field should only contain letters.',
            'last_name.regex' => 'The last name field should only contain letters.',
        ]);

        // Generate password if not provided
        $generatedPassword = Str::random(10);
        $password = ! empty($validated['password']) ? $validated['password'] : $generatedPassword;

        // Generate username from first name and last name
        $username = User::generateUniqueUsername($validated['first_name'], $validated['last_name']);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'username' => $username,
            'email' => $validated['email'],
            'password' => Hash::make($password),
            'role' => $validated['role'],
            'status' => 'active',
        ]);

        // Send account created email
        Mail::to($user->email)->send(new AccountCreated([
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'role' => $user->role,
            'password' => $password,
        ]));

        // Audit log
        AuditTrailController::log(
            'Created',
            "User: {$user->first_name} {$user->last_name} ({$user->email})",
            "Role: {$user->role}"
        );

        return redirect()->route('admin.users.index')->with('success', 'User added successfully! An email has been sent to the user.');
    }

    /**
     * Approve a pending user.
     */
    public function approve(User $user)
    {
        $user->update(['status' => 'active']);

        // Send account approved email
        Mail::to($user->email)->send(new AccountApproved($user));

        // Audit log
        AuditTrailController::log(
            'Approved',
            "User: {$user->first_name} {$user->last_name} ({$user->email})",
            'Status changed from pending to active'
        );

        return redirect()->route('admin.users.index')->with('success', 'User approved successfully! An email notification has been sent.');
    }

    /**
     * Reject a pending user.
     */
    public function reject(User $user)
    {
        $userName = "{$user->first_name} {$user->last_name} ({$user->email})";

        $user->delete();

        // Audit log
        AuditTrailController::log(
            'Rejected',
            "User: {$userName}",
            'Pending user rejected and removed'
        );

        return redirect()->route('admin.users.index')->with('success', 'User rejected successfully!');
    }

    /**
     * Update an existing user.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'in:admin,teacher,student'],
            'status' => ['required', 'in:active,suspended'],
        ], [
            'first_name.regex' => 'The first name field should only contain letters.',
            'last_name.regex' => 'The last name field should only contain letters.',
        ]);

        // Track what changed for audit
        $changes = [];
        if ($user->first_name !== $validated['first_name']) {
            $changes[] = "Name: {$user->first_name} -> {$validated['first_name']}";
        }
        if ($user->last_name !== $validated['last_name']) {
            $changes[] = "{$user->last_name} -> {$validated['last_name']}";
        }
        if ($user->email !== $validated['email']) {
            $changes[] = "Email: {$user->email} -> {$validated['email']}";
        }
        if ($user->role !== $validated['role']) {
            $changes[] = "Role: {$user->role} -> {$validated['role']}";
        }
        if ($user->status !== $validated['status']) {
            $changes[] = "Status: {$user->status} -> {$validated['status']}";
        }

        $user->update($validated);

        // Audit log
        if (! empty($changes)) {
            AuditTrailController::log(
                'Updated',
                "User: {$user->first_name} {$user->last_name} ({$user->email})",
                implode(', ', $changes)
            );
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }

    /**
     * Delete a user.
     */
    public function destroy(User $user)
    {
        $userName = "{$user->first_name} {$user->last_name} ({$user->email})";
        $userRole = $user->role;

        $user->delete();

        // Audit log
        AuditTrailController::log(
            'Deleted',
            "User: {$userName}",
            "Role: {$userRole}"
        );

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
    }

    /**
     * Display trashed users.
     */
    public function trashed(Request $request)
    {
        $query = User::onlyTrashed();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('deleted_at', 'desc')->paginate(15);

        return view('admin.user_trash', compact('users'));
    }

    /**
     * Restore a trashed user.
     */
    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $userName = "{$user->first_name} {$user->last_name}";

        $user->restore();

        AuditTrailController::log(
            'Restored',
            "User: {$userName}",
            'Restored from trash'
        );

        return redirect()->route('admin.users.trash')->with('success', 'User restored successfully!');
    }

    /**
     * Bulk restore trashed users.
     */
    public function bulkRestore(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => ['required', 'array'],
            'user_ids.*' => ['exists:users,id'],
        ]);

        $count = User::onlyTrashed()
            ->whereIn('id', $validated['user_ids'])
            ->restore();

        AuditTrailController::log(
            'Restored',
            "{$count} user(s) restored",
            'Bulk restore from trash'
        );

        return redirect()->route('admin.users.trash')->with('success', "{$count} user(s) restored successfully!");
    }

    /**
     * Permanently delete a trashed user.
     */
    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $userName = "{$user->first_name} {$user->last_name}";

        $user->forceDelete();

        AuditTrailController::log(
            'Permanently Deleted',
            "User: {$userName}",
            'Permanently deleted from trash'
        );

        return redirect()->route('admin.users.trash')->with('success', 'User permanently deleted!');
    }
}
