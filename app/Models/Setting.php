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

    protected static function booted(): void
    {
        static::saved(function (): void {
            static::$resolved = null;
            Cache::forget('settings.all');
        });
    }
}
