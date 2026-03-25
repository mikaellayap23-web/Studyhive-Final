<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Module extends Model
{
    protected $fillable = [
        'user_id',
        'assigned_teacher_id',
        'title',
        'description',
        'file_path',
        'status',
        'order',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTeacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_teacher_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function enrolledStudents(): HasMany
    {
        return $this->hasMany(Enrollment::class)->with('user');
    }

    /**
     * Get the assessment for this module (one-to-one relationship)
     */
    public function assessment(): HasOne
    {
        return $this->hasOne(Assessment::class);
    }
}
