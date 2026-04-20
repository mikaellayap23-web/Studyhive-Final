<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the profile page.
     */
    public function show(Request $request)
    {
        $auditEntries = [];
        $logPath = storage_path('logs/audit.log');
        $user = Auth::user();

        if (File::exists($logPath)) {
            $lines = File::lines($logPath);
            foreach ($lines as $line) {
                $data = json_decode($line, true);
                if ($data && isset($data['action']) && isset($data['user_id']) && $data['user_id'] == $user->id) {
                    $auditEntries[] = $data;
                }
            }
            $auditEntries = array_reverse($auditEntries);

            // Apply filters
            if ($request->filled('action')) {
                $auditEntries = array_filter($auditEntries, function ($entry) use ($request) {
                    return strtolower($entry['action']) === strtolower($request->action);
                });
            }

            if ($request->filled('date_from')) {
                $auditEntries = array_filter($auditEntries, function ($entry) use ($request) {
                    return isset($entry['timestamp']) && $entry['timestamp'] >= $request->date_from;
                });
            }

            if ($request->filled('date_to')) {
                $auditEntries = array_filter($auditEntries, function ($entry) use ($request) {
                    return isset($entry['timestamp']) && $entry['timestamp'] <= $request->date_to.' 23:59:59';
                });
            }

            $auditEntries = array_slice($auditEntries, 0, 50);
        }

        return view('pages.profile', compact('auditEntries'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'last_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'username' => ['nullable', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
        ], [
            'first_name.regex' => 'The first name field should only contain letters.',
            'last_name.regex' => 'The last name field should only contain letters.',
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Check if current password is correct
        if (! Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'The current password is incorrect.',
            ]);
        }

        // Update password
        $user->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }
}
