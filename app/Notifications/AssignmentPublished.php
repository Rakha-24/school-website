<?php

namespace App\Notifications;

use App\Models\Assignment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AssignmentPublished extends Notification
{
    use Queueable;

    public function __construct(public Assignment $assignment) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'icon' => 'clipboard',
            'title' => 'Tugas baru: '.$this->assignment->title,
            'message' => 'Tugas baru dari '.$this->assignment->teacher->user->name.' dipublikasikan. Tenggat: '.$this->assignment->due_at->translatedFormat('d M Y, H:i').'.',
            'url' => route('portal.student.assignments.show', $this->assignment),
        ];
    }
}
