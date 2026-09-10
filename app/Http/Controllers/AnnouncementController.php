<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('creator')
            ->latest()
            ->paginate(15);

        return view('announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('announcements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'body'         => 'required|string',
            'priority'     => 'required|in:normal,important,urgent',
            'published_at' => 'nullable|date',
            'expires_at'   => 'nullable|date|after:published_at',
        ]);

        Announcement::create([
            'title'        => $request->title,
            'body'         => $request->body,
            'priority'     => $request->priority,
            'is_active'    => true,
            'created_by'   => auth()->id(),
            'published_at' => $request->published_at ?? now(),
            'expires_at'   => $request->expires_at,
        ]);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement imewekwa!');
    }

    public function edit(Announcement $announcement)
    {
        return view('announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'body'         => 'required|string',
            'priority'     => 'required|in:normal,important,urgent',
            'is_active'    => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'expires_at'   => 'nullable|date|after:published_at',
        ]);

        $announcement->update([
            'title'        => $request->title,
            'body'         => $request->body,
            'priority'     => $request->priority,
            'is_active'    => $request->boolean('is_active'),
            'published_at' => $request->published_at,
            'expires_at'   => $request->expires_at,
        ]);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement imesasishwa!');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('announcements.index')
            ->with('success', 'Announcement imefutwa!');
    }

    // For all authenticated users to view
    public function board()
    {
        $announcements = Announcement::visible()
            ->with('creator')
            ->latest('published_at')
            ->paginate(10);

        return view('announcements.board', compact('announcements'));
    }
}
