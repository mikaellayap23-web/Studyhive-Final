<?php

namespace App\Http\Controllers;

use App\Mail\BulkEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class BulkEmailController extends Controller
{
    /**
     * Show bulk email form.
     */
    public function create()
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403);
        }

        $recipientCounts = [
            'all' => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'teacher' => User::where('role', 'teacher')->count(),
            'student' => User::where('role', 'student')->count(),
        ];

        return view('admin.bulk-email', compact('recipientCounts'));
    }

    /**
     * Send bulk email.
     */
    public function send(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'recipient_type' => ['required', 'in:all,admin,teacher,student'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'send_to' => ['required', 'array'],
            'send_to.*' => ['email', 'max:255'],
        ]);

        // Get recipients based on recipient_type
        if ($request->has('send_to') && count($request->send_to) > 0) {
            // Custom email list
            $recipients = $request->send_to;
        } else {
            // Role-based recipients
            $query = User::query();
            if ($validated['recipient_type'] !== 'all') {
                $query->where('role', $validated['recipient_type']);
            }
            $recipients = $query->pluck('email')->toArray();
        }

        // Send emails in background (synchronously for now)
        $sentCount = 0;
        foreach ($recipients as $email) {
            try {
                Mail::to($email)->send(new BulkEmail(
                    $validated['subject'],
                    $validated['message'],
                    $user->first_name.' '.$user->last_name
                ));
                $sentCount++;
            } catch (\Exception $e) {
                // Log error but continue
                \Log::error("Bulk email failed to {$email}: ".$e->getMessage());
            }
        }

        return redirect()->route('admin.bulk-email.create')
            ->with('success', "Bulk email sent successfully to {$sentCount} recipient(s).");
    }
}
