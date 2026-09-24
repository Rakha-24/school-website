<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AchievementController extends Controller
{
    public function index(Request $request): View
    {
        $years = Achievement::query()->published()->distinct()->orderByDesc('year')->pluck('year');

        $query = Achievement::query()->published()->latest('year')->latest('id');

        if ($request->filled('year')) {
            $query->where('year', (int) $request->query('year'));
        }

        return view('public.achievements', [
            'achievements' => $query->paginate(12)->withQueryString(),
            'years' => $years,
            'activeYear' => $request->query('year'),
        ]);
    }
}
