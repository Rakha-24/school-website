<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Assignment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'due_at' => 'datetime',
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

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function attachmentUrl(): ?string
    {
        return $this->attachment ? Storage::url($this->attachment) : null;
    }

    public function isOpen(): bool
    {
        return $this->status === 'published' && $this->due_at->isFuture();
    }

    public function isOverdue(): bool
    {
        return $this->due_at->isPast();
    }

    public function submissionFor(Student $student): ?Submission
    {
        return $this->submissions()->where('student_id', $student->id)->first();
    }
}
