<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewContactMessage extends Notification
{
    use Queueable;

    public function __construct(public ContactMessage $message) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'icon' => 'inbox',
            'title' => 'Pesan baru dari pengunjung situs',
            'message' => "{$this->message->name} mengirim pesan berjudul \"{$this->message->subject}\".",
            'url' => route('portal.admin.messages.show', $this->message),
        ];
    }
}
