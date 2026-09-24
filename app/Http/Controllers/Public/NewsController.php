<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    protected const CATEGORIES = ['Berita', 'Kegiatan', 'Pengumuman'];

    public function index(Request $request): View
    {
        $query = News::query()
            ->published()
            ->with('author')
            ->latest('published_at');

        if ($request->filled('category') && in_array($request->query('category'), self::CATEGORIES, true)) {
            $query->where('category', $request->query('category'));
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'ilike', '%'.$request->query('q').'%')
                    ->orWhere('excerpt', 'ilike', '%'.$request->query('q').'%');
            });
        }

        return view('public.news.index', [
            'news' => $query->paginate(9)->withQueryString(),
            'categories' => self::CATEGORIES,
            'activeCategory' => $request->query('category'),
            'query' => $request->query('q'),
        ]);
    }

    public function show(News $news): View
    {
        abort_unless($news->isPublished(), 404);

        return view('public.news.show', [
            'news' => $news->load('author'),
            'related' => News::query()
                ->published()
                ->whereKeyNot($news->id)
                ->where('category', $news->category)
                ->latest('published_at')
                ->take(3)
                ->get(),
        ]);
    }
}
