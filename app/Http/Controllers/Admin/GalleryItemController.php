<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GalleryItemRequest;
use App\Models\GalleryItem;
use App\Traits\HandlesUploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GalleryItemController extends Controller
{
    use HandlesUploads;

    public function index(): View
    {
        return view('admin.gallery.index', [
            'items' => GalleryItem::query()->latest('published_at')->paginate(24),
            'categories' => ['Kegiatan', 'Pembelajaran', 'Prestasi', 'Kampus', 'Ekstrakurikuler'],
        ]);
    }

    public function create(): View
    {
        return view('admin.gallery.form', [
            'item' => null,
            'categories' => ['Kegiatan', 'Pembelajaran', 'Prestasi', 'Kampus', 'Ekstrakurikuler'],
        ]);
    }

    public function store(GalleryItemRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['image']);
        $data['image'] = $this->storeSanitizedFile($request->file('image'), 'gallery');
        $data['published_at'] = $request->input('status') === 'published' ? now() : null;

        GalleryItem::query()->create($data);

        return redirect()->route('portal.admin.gallery.index')->with('status', 'Foto galeri berhasil ditambahkan.');
    }

    public function edit(GalleryItem $gallery): View
    {
        return view('admin.gallery.form', [
            'item' => $gallery,
            'categories' => ['Kegiatan', 'Pembelajaran', 'Prestasi', 'Kampus', 'Ekstrakurikuler'],
        ]);
    }

    public function update(GalleryItemRequest $request, GalleryItem $gallery): RedirectResponse
    {
        $data = $request->safe()->except(['image']);
        $data['published_at'] = $request->input('status') === 'published' ? ($gallery->published_at ?? now()) : null;

        if ($request->hasFile('image')) {
            $this->deleteStoredFile($gallery->image);
            $data['image'] = $this->storeSanitizedFile($request->file('image'), 'gallery');
        }

        $gallery->update($data);

        return redirect()->route('portal.admin.gallery.index')->with('status', 'Foto galeri berhasil diperbarui.');
    }

    public function destroy(GalleryItem $gallery): RedirectResponse
    {
        $this->deleteStoredFile($gallery->image);
        $gallery->delete();

        return redirect()->route('portal.admin.gallery.index')->with('status', 'Foto galeri telah dihapus.');
    }
}
