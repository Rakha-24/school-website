<?php

namespace App\Notifications;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SubmissionGraded extends Notification
{
    use Queueable;

    public function __construct(public Submission $submission) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'icon' => 'check-circle',
            'title' => 'Tugas Anda telah dinilai',
            'message' => 'Tugas "'.$this->submission->assignment->title.'" dinilai '.($this->submission->score ?? '—').'. Lihat umpan balik guru.',
            'url' => route('portal.student.grades.index'),
        ];
    }
}
