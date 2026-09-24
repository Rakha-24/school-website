<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    use HasFactory;

    public const AUDIENCE_PUBLIC = 'public';

    public const AUDIENCE_STUDENTS = 'students';

    public const AUDIENCE_TEACHERS = 'teachers';

    public const AUDIENCE_ALL = 'all';

    public const AUDIENCES = [
        self::AUDIENCE_PUBLIC => 'Publik',
        self::AUDIENCE_STUDENTS => 'Siswa',
        self::AUDIENCE_TEACHERS => 'Guru',
        self::AUDIENCE_ALL => 'Siswa & Guru',
    ];

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function isPublished(): bool
    {
        return $this->status === 'published' && $this->published_at?->lte(now());
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    /**
     * @param  array<string>  $roles
     */
    public function visibleToRoles(array $roles): bool
    {
        if (! $this->isPublished()) {
            return false;
        }

        return match ($this->audience) {
            self::AUDIENCE_PUBLIC, self::AUDIENCE_ALL => true,
            self::AUDIENCE_STUDENTS => in_array('student', $roles),
            self::AUDIENCE_TEACHERS => in_array('teacher', $roles),
            default => false,
        };
    }
}
