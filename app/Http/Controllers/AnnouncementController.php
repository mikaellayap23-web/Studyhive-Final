<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\AnnouncementRead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * Display announcements.
     */
    public function index(Request $request)
    {
        $query = Announcement::with('user');

        // Apply filters
        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }
        if ($request->filled('author')) {
            $query->where('user_id', $request->author);
        }

        $announcements = $query->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($announcement) {
                $isOwn = $announcement->user_id === Auth::id();
                $announcement->is_read = $isOwn || $announcement->reads()
                    ->where('user_id', Auth::id())
                    ->exists();
                return $announcement;
            });

        return view('announcements.index', compact('announcements'));
    }

    /**
     * Mark a single announcement as read.
     */
    public function markAsRead(Announcement $announcement)
    {
        AnnouncementRead::firstOrCreate([
            'user_id' => Auth::id(),
            'announcement_id' => $announcement->id,
        ]);

        return redirect()->route('announcements.index');
    }

    /**
     * Store a new announcement.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        Announcement::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        return redirect()->route('announcements.index')->with('success', 'Announcement posted successfully!');
    }

    /**
     * Update an announcement.
     */
    public function update(Request $request, Announcement $announcement)
    {
        // Only allow admin or the creator to edit
        if (Auth::user()->role !== 'admin' && Auth::id() !== $announcement->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        $announcement->update($validated);

        return redirect()->route('announcements.index')->with('success', 'Announcement updated successfully!');
    }

    /**
     * Delete an announcement.
     */
    public function destroy(Announcement $announcement)
    {
        // Only allow admin or the creator to delete
        if (Auth::user()->role !== 'admin' && Auth::id() !== $announcement->user_id) {
            abort(403);
        }

        $announcement->delete();

        return redirect()->route('announcements.index')->with('success', 'Announcement deleted successfully!');
    }
}
