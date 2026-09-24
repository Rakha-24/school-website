<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NewsRequest;
use App\Models\News;
use App\Traits\HandlesUploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NewsController extends Controller
{
    use HandlesUploads;

    public function index(): View
    {
        return view('admin.news.index', [
            'news' => News::query()->with('author')->latest('published_at')->paginate(20),
            'categories' => ['Berita', 'Kegiatan', 'Pengumuman'],
        ]);
    }

    public function create(): View
    {
        return view('admin.news.form', [
            'news' => null,
            'categories' => ['Berita', 'Kegiatan', 'Pengumuman'],
        ]);
    }

    public function store(NewsRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['cover_image']);
        $data['author_id'] = auth()->id();
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['published_at'] = $request->input('status') === 'published' ? now() : $request->date('published_at');

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $this->storeSanitizedFile($request->file('cover_image'), 'news');
        }

        News::query()->create($data);

        return redirect()->route('portal.admin.news.index')->with('status', 'Berita berhasil dibuat.');
    }

    public function edit(News $news): View
    {
        return view('admin.news.form', [
            'news' => $news,
            'categories' => ['Berita', 'Kegiatan', 'Pengumuman'],
        ]);
    }

    public function update(NewsRequest $request, News $news): RedirectResponse
    {
        $data = $request->safe()->except(['cover_image']);
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['published_at'] = $request->input('status') === 'published' ? ($news->published_at ?? now()) : null;

        if ($request->hasFile('cover_image')) {
            $this->deleteStoredFile($news->cover_image);
            $data['cover_image'] = $this->storeSanitizedFile($request->file('cover_image'), 'news');
        }

        $news->update($data);

        return redirect()->route('portal.admin.news.index')->with('status', 'Berita diperbarui.');
    }

    public function destroy(News $news): RedirectResponse
    {
        $this->deleteStoredFile($news->cover_image);
        $news->delete();

        return redirect()->route('portal.admin.news.index')->with('status', 'Berita telah dihapus.');
    }
}
