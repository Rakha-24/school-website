<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HandlesUploads
{
    protected function storeSanitizedFile(?UploadedFile $file, string $directory): ?string
    {
        if (! $file) {
            return null;
        }

        $original = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension() ?: $file->guessExtension();
        $stem = Str::slug(pathinfo($original, PATHINFO_FILENAME)) ?: Str::random(12);
        $name = $stem.'-'.Str::random(10).'.'.$extension;

        return $file->storePubliclyAs($directory, $name, 'public');
    }

    protected function deleteStoredFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
