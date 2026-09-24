<?php

namespace App\Notifications;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AnnouncementPublished extends Notification
{
    use Queueable;

    public function __construct(public Announcement $announcement) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $url = match ($notifiable->role) {
            'admin' => route('portal.admin.announcements.show', $this->announcement),
            'teacher' => route('portal.teacher.announcements.show', $this->announcement),
            default => route('portal.student.announcements.show', $this->announcement),
        };

        return [
            'icon' => 'megaphone',
            'title' => $this->announcement->title,
            'message' => 'Pengumuman baru untuk Anda.',
            'url' => $url,
        ];
    }
}
