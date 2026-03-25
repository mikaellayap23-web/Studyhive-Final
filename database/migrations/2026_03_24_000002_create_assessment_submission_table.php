<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assessment_submission', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('assessment')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('attempt_number')->default(1); // Track which attempt this is
            $table->json('answers'); // Student's answers in JSON format
            $table->decimal('score', 5, 2)->default(0); // Actual score
            $table->decimal('total_points', 5, 2)->default(0); // Total possible points
            $table->decimal('percentage', 5, 2)->default(0); // Score percentage
            $table->enum('status', ['pending', 'graded', 'passed', 'failed'])->default('pending');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            // Index for faster lookups (allows multiple attempts per user)
            $table->index(['assessment_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_submission');
    }
};
