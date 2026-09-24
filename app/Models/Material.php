<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Material extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function classSubject(): BelongsTo
    {
        return $this->belongsTo(ClassSubject::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function isPublished(): bool
    {
        return $this->status === 'published' && $this->published_at?->lte(now());
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function fileUrl(): ?string
    {
        return $this->file_path ? Storage::url($this->file_path) : null;
    }

    public function fileSize(): ?int
    {
        if (! $this->file_path || ! Storage::exists($this->file_path)) {
            return null;
        }

        return Storage::size($this->file_path);
    }
}
