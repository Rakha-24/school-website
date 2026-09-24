<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Announcement\AnnouncementRequest;
use App\Models\Announcement;
use App\Models\User;
use App\Notifications\AnnouncementPublished;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(): View
    {
        $announcements = Announcement::query()
            ->with('author')
            ->latest('published_at')
            ->get()
            ->filter(function ($a) {
                if ($a->author_id === Auth::id()) {
                    return true;
                }

                return $a->visibleToRoles(['teacher']);
            });

        return view('teacher.announcements.index', [
            'announcements' => $announcements,
        ]);
    }

    public function create(): View
    {
        $this->authorize('manage-announcements');

        $audiences = Announcement::AUDIENCES;
        unset($audiences[Announcement::AUDIENCE_ALL]);

        return view('teacher.announcements.form', [
            'announcement' => null,
            'audiences' => $audiences,
        ]);
    }

    public function store(AnnouncementRequest $request): RedirectResponse
    {
        $this->authorize('manage-announcements');

        $announcement = Announcement::query()->create(array_merge(
            $request->validated(),
            ['author_id' => Auth::id()],
            ['published_at' => $request->input('status') === 'published' ? now() : null]
        ));

        if ($announcement->isPublished()) {
            $roles = match ($announcement->audience) {
                Announcement::AUDIENCE_STUDENTS => [User::ROLE_STUDENT],
                Announcement::AUDIENCE_TEACHERS => [User::ROLE_TEACHER],
                default => [User::ROLE_STUDENT, User::ROLE_TEACHER],
            };

            Notification::send(User::query()->whereIn('role', $roles)->get(), new AnnouncementPublished($announcement));
        }

        return redirect()->route('portal.teacher.announcements.index')->with('status', 'Pengumuman berhasil diterbitkan.');
    }

    public function show(Announcement $announcement): View
    {
        abort_unless(
            $announcement->author_id === Auth::id() || $announcement->visibleToRoles(['teacher']),
            403
        );

        return view('teacher.announcements.show', [
            'announcement' => $announcement->load('author'),
        ]);
    }
}
