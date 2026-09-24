<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        return view('portal.notifications', [
            'notifications' => auth()->user()
                ->notifications()
                ->latest()
                ->paginate(20),
        ]);
    }

    public function markRead(DatabaseNotification $notification): RedirectResponse
    {
        abort_unless($notification->notifiable_id === auth()->id(), 403);

        $notification->markAsRead();

        $url = $notification->data['url'] ?? route('portal.home');

        return redirect()->to($url);
    }
}
