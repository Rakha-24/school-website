<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

trait HandlesUploads
{
    /**
     * Pesan yang tampil ke pengguna ketika public disk tidak bisa dipakai.
     *
     * Di Vercel filesystem serverless hanya dapat ditulis di /tmp. Karena itu
     * pemanggilan Storage::disk('public') pertama sudah gagal di constructor
     * adapter Flysystem (UnableToCreateDirectory) — sebelum ada kesempatan
     * menulis file. Kegagalan itu tidak ditutupi flag 'throw' => false karena
     * tidak terjadi pada jalur write, sehingga tanpa guard di sini pengguna
     * hanya melihat "Server Error" tanpa penjelasan.
     */
    protected function uploadStorageErrorMessage(): string
    {
        return 'Server tidak dapat menyimpan berkas karena folder penyimpanan tidak dapat ditulis. '
            .'Hubungi administrator untuk mengaktifkan object storage.';
    }

    /**
     * Nama field form yang menerima berkas, supaya pesan error menempel di
     * input yang tepat. Dicari dari request karena UploadedFile tidak menyimpan
     * nama field-nya.
     */
    protected function resolveUploadField(UploadedFile $file): string
    {
        foreach (request()->allFiles() as $key => $candidate) {
            if ($candidate === $file && is_string($key)) {
                return $key;
            }
        }

        return 'file';
    }

    protected function storeSanitizedFile(?UploadedFile $file, string $directory): ?string
    {
        if (! $file) {
            return null;
        }

        $original = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension() ?: $file->guessExtension();
        $stem = Str::slug(pathinfo($original, PATHINFO_FILENAME)) ?: Str::random(12);
        $name = $stem.'-'.Str::random(10).'.'.$extension;

        try {
            $path = $file->storePubliclyAs($directory, $name, 'public');
        } catch (Throwable) {
            throw ValidationException::withMessages([
                $this->resolveUploadField($file) => $this->uploadStorageErrorMessage(),
            ]);
        }

        if (! is_string($path) || $path === '') {
            throw ValidationException::withMessages([
                $this->resolveUploadField($file) => $this->uploadStorageErrorMessage(),
            ]);
        }

        return $path;
    }

    /**
     * Menghapus berkas lama. Ini housekeeping, jadi kegagalan tidak boleh
     * membatalkan proses simpan yang baru — dan path yang bukan path milik
     * public disk (mis. nilai rusak di database) dilewati.
     */
    protected function deleteStoredFile(?string $path): void
    {
        if (! static::isManagedPublicPath($path)) {
            return;
        }

        try {
            Storage::disk('public')->delete($path);
        } catch (Throwable) {
            // Sengaja diabaikan: file lama yang tertinggal lebih baik
            // daripada request gagal.
        }
    }

    /**
     * Hanya path relatif polos yang dianggap milik public disk.
     */
    protected static function isManagedPublicPath(mixed $path): bool
    {
        if (! is_string($path) || trim($path) === '') {
            return false;
        }

        $value = trim($path);

        return ! str_contains($value, '..')
            && ! str_starts_with($value, '/')
            && ! str_starts_with($value, '\\')
            && preg_match('#^[a-zA-Z]:#', $value) !== 1
            && preg_match('#^[a-z][a-z0-9+.\-]*://#i', $value) !== 1;
    }
}
