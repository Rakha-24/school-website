<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Peta pengaturan yang sudah di-resolve untuk request berjalan, sehingga
     * membaca beberapa key (logo, hero, PPDB) hanya membutuhkan satu lookup
     * alih-alih satu round-trip database per key.
     */
    protected static ?array $resolved = null;

    public static function get(string $key, ?string $default = null): ?string
    {
        return static::map()[$key] ?? $default;
    }

    public static function map(): array
    {
        if (static::$resolved !== null) {
            return static::$resolved;
        }

        return static::$resolved = Cache::rememberForever('settings.all', function () {
            return static::pluck('value', 'key')->all();
        });
    }

    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    /**
     * Nilai setting yang dipakai sebagai path di dalam public disk.
     *
     * Nilai yang dikembalikan selalu path relatif yang aman untuk disisipkan ke
     * URL aset. Path absolut, traversal, atau URL eksternal ditolak karena
     * berarti nilainya bukan rujukan upload yang sah — memakainya akan
     * menghasilkan <img> yang rusak, bukan gambar.
     */
    public static function path(string $key): ?string
    {
        $value = static::get($key);

        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        $value = trim($value);

        $rejected = str_contains($value, '..')
            || str_starts_with($value, '/')
            || str_starts_with($value, '\\')
            || preg_match('#^[a-zA-Z]:#', $value) === 1
            || preg_match('#^[a-z][a-z0-9+.\-]*://#i', $value) === 1;

        return $rejected ? null : $value;
    }

    protected static function booted(): void
    {
        static::saved(function (): void {
            static::$resolved = null;
            Cache::forget('settings.all');
        });
    }
}
