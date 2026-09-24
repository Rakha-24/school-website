<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Submission extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'graded_at' => 'datetime',
            'score' => 'integer',
        ];
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function fileUrl(): ?string
    {
        return $this->file_path ? Storage::url($this->file_path) : null;
    }

    public function isGraded(): bool
    {
        return $this->score !== null;
    }

    public function isLate(): bool
    {
        return $this->submitted_at?->gt($this->assignment?->due_at) ?? false;
    }
}
