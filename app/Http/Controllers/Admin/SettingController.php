<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Traits\HandlesUploads;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class SettingController extends Controller
{
    use HandlesUploads;

    public function edit(): View
    {
        return view('admin.settings', [
            'settings' => config('school.settings', []),
            'logo' => Setting::get('logo'),
        ]);
    }

    public function update(): RedirectResponse
    {
        $validator = Validator::make(request()->all(), [
            'school_name' => ['required', 'string', 'max:120'],
            'tagline' => ['nullable', 'string', 'max:180'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:160'],
            'ppdb_open' => ['nullable', 'in:on,1'],
            'ppdb_year' => ['nullable', 'string', 'max:20'],
            'hero_headline' => ['nullable', 'string', 'max:200'],
            'logo' => ['nullable', 'file', 'image', 'mimes:jpeg,jpg,png,webp,svg', 'max:2048'],
            'logo_remove' => ['nullable', 'in:on,1'],
        ]);

        $validator->validate();

        foreach ($validator->validated() as $key => $value) {
            if ($key === 'ppdb_open') {
                $value = '1';
            }
            Setting::set($key, (string) $value);
        }

        if (request()->boolean('logo_remove')) {
            $this->deleteStoredFile(Setting::get('logo'));
            Setting::set('logo', null);
        } elseif (request()->hasFile('logo')) {
            $this->deleteStoredFile(Setting::get('logo'));
            Setting::set('logo', $this->storeSanitizedFile(request()->file('logo'), 'settings'));
        }

        return back()->with('status', 'Pengaturan sekolah berhasil disimpan.');
    }
}
