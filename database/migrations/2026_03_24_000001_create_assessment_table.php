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
        Schema::create('assessment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('questions'); // Native JSON type for faster parsing
            $table->integer('duration_minutes')->default(60); // Time limit in minutes
            $table->integer('passing_score')->default(75); // Passing score percentage
            $table->integer('max_attempts')->default(1); // Max attempts (0 = unlimited)
            $table->boolean('is_published')->default(false);
            $table->boolean('show_correct_answer')->default(false); // Show answers after max attempts
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment');
    }
};
