<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\AssessmentSubmission;
use Illuminate\Support\Facades\DB;

class GradeService
{
    /**
     * Auto-grade a submission.
     */
    public function grade(AssessmentSubmission $submission): AssessmentSubmission
    {
        $assessment = $submission->assessment;
        $questions = $assessment->questions ?? [];
        $answers = $submission->answers ?? [];

        $score = 0;
        $totalPoints = 0;

        foreach ($questions as $question) {
            $questionId = $question['id'] ?? null;
            if (! $questionId || ! isset($answers[$questionId])) {
                continue;
            }

            $questionPoints = $question['points'] ?? 1;
            $totalPoints += $questionPoints;

            $correctAnswer = $question['correct_answer'] ?? null;
            $userAnswer = $answers[$questionId];

            if ($this->isCorrect($correctAnswer, $userAnswer, $question['type'] ?? 'multiple_choice')) {
                $score += $questionPoints;
            }
        }

        $percentage = $totalPoints > 0 ? ($score / $totalPoints) * 100 : 0;
        $status = $percentage >= $assessment->passing_score ? 'passed' : 'failed';

        $submission->update([
            'score' => $score,
            'total_points' => $totalPoints,
            'percentage' => $percentage,
            'status' => $status,
        ]);

        return $submission;
    }

    /**
     * Check if user answer is correct.
     */
    protected function isCorrect($correctAnswer, $userAnswer, string $questionType): bool
    {
        // Handle array answers (for multiple correct answers)
        if (is_array($correctAnswer)) {
            return in_array($userAnswer, $correctAnswer);
        }

        switch ($questionType) {
            case 'multiple_choice':
            case 'true_false':
                return (string) $correctAnswer === (string) $userAnswer;

            case 'fill_blank':
                return strtolower(trim($correctAnswer)) === strtolower(trim($userAnswer));

            default:
                return (string) $correctAnswer === (string) $userAnswer;
        }
    }

    /**
     * Process a submission with transaction.
     */
    public function processSubmission(Assessment $assessment, int $userId): AssessmentSubmission
    {
        return DB::transaction(function () use ($assessment, $userId) {
            $attemptNumber = AssessmentSubmission::getNextAttemptNumber($assessment->id, $userId);

            $answers = request('answers', []);

            $submission = AssessmentSubmission::create([
                'assessment_id' => $assessment->id,
                'user_id' => $userId,
                'attempt_number' => $attemptNumber,
                'answers' => $answers,
                'submitted_at' => now(),
            ]);

            return $this->grade($submission);
        });
    }
}
