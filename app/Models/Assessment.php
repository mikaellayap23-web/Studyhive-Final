<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
    protected $table = 'assessment';

    protected $fillable = [
        'module_id',
        'created_by',
        'title',
        'description',
        'questions',
        'duration_minutes',
        'passing_score',
        'max_attempts',
        'is_published',
    ];

    protected $casts = [
        'questions' => 'array',
        'is_published' => 'boolean',
        'duration_minutes' => 'integer',
        'passing_score' => 'integer',
        'max_attempts' => 'integer',
    ];

    /**
     * Get the module that owns this assessment
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Get the user who created this assessment
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all submissions for this assessment
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(AssessmentSubmission::class);
    }

    /**
     * Get user's submissions for this assessment
     */
    public function userSubmissions(User $user): HasMany
    {
        return $this->hasMany(AssessmentSubmission::class)->where('user_id', $user->id);
    }

    /**
     * Get user's latest submission
     */
    public function latestSubmission(User $user): ?AssessmentSubmission
    {
        return $this->userSubmissions($user)->latest('submitted_at')->first();
    }

    /**
     * Get user's best submission (highest percentage)
     */
    public function bestSubmission(User $user): ?AssessmentSubmission
    {
        return $this->userSubmissions($user)->orderBy('percentage', 'desc')->first();
    }

    /**
     * Get number of attempts user has taken
     */
    public function getUserAttempts(User $user): int
    {
        return $this->userSubmissions($user)->count();
    }

    /**
     * Check if user can take assessment
     */
    public function canUserTake(User $user): bool
    {
        // If max_attempts is 0 or null, unlimited attempts allowed
        if (!$this->max_attempts || $this->max_attempts <= 0) {
            return true;
        }

        // Check if user has attempts remaining
        return $this->getUserAttempts($user) < $this->max_attempts;
    }

    /**
     * Get user's remaining attempts
     */
    public function getRemainingAttempts(User $user): int
    {
        // If max_attempts is 0 or null, unlimited attempts
        if (!$this->max_attempts || $this->max_attempts <= 0) {
            return -1; // -1 represents unlimited
        }

        $remaining = $this->max_attempts - $this->getUserAttempts($user);
        return max(0, $remaining);
    }

    /**
     * Check if user can edit/delete this assessment
     */
    public function canEdit(User $user): bool
    {
        // Admin can edit all
        if ($user->role === 'admin') {
            return true;
        }

        // Teacher can only edit their own assessments
        return $user->id === $this->created_by;
    }
}
