<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Extracurricular;
use App\Models\News;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Beranda publik situs sekolah. Pengguna yang sudah masuk diarahkan
     * ke portal sesuai perannya agar tidak bertemu dua "beranda".
     */
    public function show(): RedirectResponse|View
    {
        if (Auth::check()) {
            return redirect()->route('portal.home');
        }

        return view('public.home', [
            'heroImage' => Setting::get('home_hero_image', 'seeds/hero.jpg'),
            'latestNews' => News::query()->published()->latest('published_at')->take(3)->get(),
            'featuredAchievements' => Achievement::query()->latest()->take(3)->get(),
            'extracurriculars' => Extracurricular::query()->orderBy('name')->get(),
            'programCount' => 3,
        ]);
    }

    /**
     * Titik masuk portal umum: mengarahkan user ke dashboard sesuai role.
     */
    public function portal(): RedirectResponse
    {
        return redirect()->route(match (Auth::user()->role) {
            User::ROLE_ADMIN => 'portal.admin.dashboard',
            User::ROLE_TEACHER => 'portal.teacher.dashboard',
            default => 'portal.student.dashboard',
        });
    }
}
