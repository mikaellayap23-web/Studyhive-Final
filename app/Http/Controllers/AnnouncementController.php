<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\AnnouncementRead;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Display announcements.
     */
    public function index()
    {
        // Mark all announcements as read for the current user
        $unreadAnnouncements = Announcement::whereDoesntHave('reads', function ($query) {
            $query->where('user_id', auth()->id());
        })->get();

        foreach ($unreadAnnouncements as $announcement) {
            AnnouncementRead::create([
                'user_id' => auth()->id(),
                'announcement_id' => $announcement->id,
            ]);
        }

        $announcements = Announcement::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('announcements.index', compact('announcements'));
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
            'user_id' => auth()->id(),
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
        if (auth()->user()->role !== 'admin' && auth()->id() !== $announcement->user_id) {
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
        if (auth()->user()->role !== 'admin' && auth()->id() !== $announcement->user_id) {
            abort(403);
        }

        $announcement->delete();

        return redirect()->route('announcements.index')->with('success', 'Announcement deleted successfully!');
    }
}
