<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(Request $request): View
    {
        $categories = GalleryItem::query()
            ->published()
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $query = GalleryItem::query()->published()->latest('published_at');

        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }

        return view('public.gallery', [
            'items' => $query->paginate(12)->withQueryString(),
            'categories' => $categories,
            'activeCategory' => $request->query('category'),
        ]);
    }
}
