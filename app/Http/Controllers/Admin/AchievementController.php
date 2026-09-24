<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AchievementRequest;
use App\Models\Achievement;
use App\Traits\HandlesUploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AchievementController extends Controller
{
    use HandlesUploads;

    public function index(): View
    {
        return view('admin.achievements.index', [
            'achievements' => Achievement::query()->latest('year')->paginate(20),
            'levels' => ['city' => 'Kota', 'district' => 'Kecamatan', 'province' => 'Provinsi', 'national' => 'Nasional', 'international' => 'Internasional'],
        ]);
    }

    public function create(): View
    {
        return view('admin.achievements.form', [
            'achievement' => null,
            'levels' => ['city' => 'Kota', 'district' => 'Kecamatan', 'province' => 'Provinsi', 'national' => 'Nasional', 'international' => 'Internasional'],
        ]);
    }

    public function store(AchievementRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['image']);
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['published_at'] = $request->input('status') === 'published' ? now() : $request->date('published_at');

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeSanitizedFile($request->file('image'), 'achievements');
        }

        Achievement::query()->create($data);

        return redirect()->route('portal.admin.achievements.index')->with('status', 'Prestasi berhasil ditambahkan.');
    }

    public function edit(Achievement $achievement): View
    {
        return view('admin.achievements.form', [
            'achievement' => $achievement,
            'levels' => ['city' => 'Kota', 'district' => 'Kecamatan', 'province' => 'Provinsi', 'national' => 'Nasional', 'international' => 'Internasional'],
        ]);
    }

    public function update(AchievementRequest $request, Achievement $achievement): RedirectResponse
    {
        $data = $request->safe()->except(['image']);
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['published_at'] = $request->input('status') === 'published' ? ($achievement->published_at ?? now()) : null;

        if ($request->hasFile('image')) {
            $this->deleteStoredFile($achievement->image);
            $data['image'] = $this->storeSanitizedFile($request->file('image'), 'achievements');
        }

        $achievement->update($data);

        return redirect()->route('portal.admin.achievements.index')->with('status', 'Prestasi diperbarui.');
    }

    public function destroy(Achievement $achievement): RedirectResponse
    {
        $this->deleteStoredFile($achievement->image);
        $achievement->delete();

        return redirect()->route('portal.admin.achievements.index')->with('status', 'Prestasi telah dihapus.');
    }
}
