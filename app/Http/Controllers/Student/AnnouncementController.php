<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(): View
    {
        $announcements = Announcement::query()
            ->with('author')
            ->latest('published_at')
            ->get()
            ->filter(fn ($a) => $a->visibleToRoles([Auth::user()->role]));

        return view('student.announcements.index', [
            'announcements' => $announcements,
        ]);
    }

    public function show(Announcement $announcement): View
    {
        abort_unless($announcement->visibleToRoles([Auth::user()->role]), 404);

        return view('student.announcements.show', [
            'announcement' => $announcement->load('author'),
        ]);
    }
}
