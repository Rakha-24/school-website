<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance';

    public const STATUS_PRESENT = 'present';

    public const STATUS_SICK = 'sick';

    public const STATUS_PERMISSION = 'permission';

    public const STATUS_ABSENT = 'absent';

    public const STATUSES = [
        self::STATUS_PRESENT => 'Hadir',
        self::STATUS_SICK => 'Sakit',
        self::STATUS_PERMISSION => 'Izin',
        self::STATUS_ABSENT => 'Alpa',
    ];

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
