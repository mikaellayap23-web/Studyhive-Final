<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'assigned_teacher_id',
        'prerequisite_module_id',
        'title',
        'description',
        'image_path',
        'file_path',
        'status',
        'order',
    ];

    protected $dates = ['deleted_at'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTeacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_teacher_id');
    }

    /**
     * Prerequisite module (must be completed before enrolling)
     */
    public function prerequisite(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'prerequisite_module_id');
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

    /**
     * Get progress records for this module
     */
    public function progress(): HasMany
    {
        return $this->hasMany(ModuleProgress::class);
    }

    /**
     * Check if a user can manage this module (edit/update/delete/manage students)
     */
    public function canManage(User $user): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        // Teacher can manage if they're assigned or created the module
        return $this->assigned_teacher_id === $user->id || $this->user_id === $user->id;
    }

    /**
     * Check if module is trashed
     */
    public function isTrashed(): bool
    {
        return $this->trashed();
    }
}
