<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Certificate extends Model
{
    protected $fillable = [
        'user_id',
        'module_id',
        'certificate_number',
        'title',
        'issue_date',
        'status',
        'pdf_path',
        'metadata',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public static function generateCertificateNumber(): string
    {
        do {
            $number = 'CERT-' . date('Y') . '-' . strtoupper(Str::random(6));
        } while (self::where('certificate_number', $number)->exists());

        return $number;
    }
}
