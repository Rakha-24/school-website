<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmissionInformation extends Model
{
    use HasFactory;

    public const TYPES = [
        'info' => 'Informasi Umum',
        'requirement' => 'Persyaratan',
        'stage' => 'Tahapan Pendaftaran',
        'schedule' => 'Jadwal',
        'document' => 'Dokumen Pendaftaran',
    ];

    protected $guarded = [];

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
