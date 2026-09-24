<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Extracurricular;
use Illuminate\View\View;

class ExtracurricularController extends Controller
{
    public function index(): View
    {
        return view('public.extracurriculars', [
            'extracurriculars' => Extracurricular::query()
                ->active()
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function show(Extracurricular $extracurricular): View
    {
        abort_unless($extracurricular->status === 'active', 404);

        return view('public.extracurricular-show', [
            'extracurricular' => $extracurricular,
            'others' => Extracurricular::query()
                ->active()
                ->whereKeyNot($extracurricular->id)
                ->orderBy('name')
                ->take(6)
                ->get(),
        ]);
    }
}
