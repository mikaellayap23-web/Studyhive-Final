<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleProgress extends Model
{
    protected $table = 'module_progress';

    protected $fillable = [
        'user_id',
        'module_id',
        'pdf_total_pages',
        'pdf_current_page',
        'pdf_completed',
        'progress',
    ];

    protected $casts = [
        'pdf_completed' => 'boolean',
        'pdf_total_pages' => 'integer',
        'pdf_current_page' => 'integer',
        'progress' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
}
