<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function get(string $key, ?string $default = null): ?string
    {
        return static::cache()->rememberForever('setting.'.$key, function () use ($key, $default) {
            return static::where('key', $key)->value('value') ?? $default;
        });
    }

    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        static::cache()->forget('setting.'.$key);
    }

    private static function cache()
    {
        return Cache::store('database');
    }

    protected static function booted(): void
    {
        static::saved(fn () => static::cache()->flush());
    }
}
