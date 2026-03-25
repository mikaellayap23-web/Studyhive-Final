<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentSubmission extends Model
{
    protected $table = 'assessment_submission';

    protected $fillable = [
        'assessment_id',
        'user_id',
        'attempt_number',
        'answers',
        'score',
        'total_points',
        'percentage',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'answers' => 'array',
        'score' => 'decimal:2',
        'total_points' => 'decimal:2',
        'percentage' => 'decimal:2',
        'submitted_at' => 'datetime',
        'attempt_number' => 'integer',
    ];

    /**
     * Get the assessment this submission belongs to
     */
    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    /**
     * Get the student who made this submission
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if submission is passing
     */
    public function isPassing(): bool
    {
        return $this->percentage >= $this->assessment->passing_score;
    }

    /**
     * Get the next attempt number for this user and assessment
     */
    public static function getNextAttemptNumber(int $assessmentId, int $userId): int
    {
        $lastSubmission = self::where('assessment_id', $assessmentId)
            ->where('user_id', $userId)
            ->orderBy('attempt_number', 'desc')
            ->first();

        return $lastSubmission ? $lastSubmission->attempt_number + 1 : 1;
    }
}
