<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->string('certificate_number')->unique();
            $table->string('title');
            $table->date('issue_date');
            $table->string('status')->default('issued');
            $table->string('pdf_path')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'module_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
