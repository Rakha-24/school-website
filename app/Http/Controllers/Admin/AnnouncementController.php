<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AnnouncementRequest as AdminAnnouncementRequest;
use App\Models\Announcement;
use App\Models\User;
use App\Notifications\AnnouncementPublished;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(): View
    {
        return view('admin.announcements.index', [
            'announcements' => Announcement::query()->with('author')->latest('published_at')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.announcements.form', [
            'announcement' => null,
            'audiences' => Announcement::AUDIENCES,
        ]);
    }

    public function store(AdminAnnouncementRequest $request): RedirectResponse
    {
        $announcement = Announcement::query()->create(array_merge(
            $request->validated(),
            ['author_id' => auth()->id()],
            ['published_at' => $request->input('status') === 'published' ? now() : $request->date('published_at')]
        ));

        if ($announcement->isPublished()) {
            $roles = match ($announcement->audience) {
                Announcement::AUDIENCE_PUBLIC => [User::ROLE_STUDENT, User::ROLE_TEACHER],
                Announcement::AUDIENCE_STUDENTS => [User::ROLE_STUDENT],
                Announcement::AUDIENCE_TEACHERS => [User::ROLE_TEACHER],
                default => [User::ROLE_STUDENT, User::ROLE_TEACHER],
            };

            Notification::send(User::query()->whereIn('role', $roles)->get(), new AnnouncementPublished($announcement));
        }

        return redirect()->route('portal.admin.announcements.index')->with('status', 'Pengumuman berhasil dibuat.');
    }

    public function edit(Announcement $announcement): View
    {
        return view('admin.announcements.form', [
            'announcement' => $announcement,
            'audiences' => Announcement::AUDIENCES,
        ]);
    }

    public function update(AdminAnnouncementRequest $request, Announcement $announcement): RedirectResponse
    {
        $announcement->update(array_merge(
            $request->validated(),
            ['published_at' => $request->input('status') === 'published' ? ($announcement->published_at ?? now()) : null]
        ));

        return redirect()->route('portal.admin.announcements.index')->with('status', 'Pengumuman diperbarui.');
    }

    public function destroy(Announcement $announcement): RedirectResponse
    {
        $announcement->delete();

        return redirect()->route('portal.admin.announcements.index')->with('status', 'Pengumuman telah dihapus.');
    }
}
