<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\ContactMessageRequest;
use App\Models\ContactMessage;
use App\Models\User;
use App\Notifications\NewContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('public.contact');
    }

    public function store(ContactMessageRequest $request): RedirectResponse
    {
        $message = ContactMessage::query()->create($request->validated());

        Notification::send(
            User::query()->role(User::ROLE_ADMIN)->get(),
            new NewContactMessage($message)
        );

        return back()->with('status', 'Terima kasih! Pesan Anda telah terkirim dan akan segera kami baca.');
    }
}
