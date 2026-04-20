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
        Schema::table('modules', function (Blueprint $table) {
            $table->foreignId('prerequisite_module_id')
                ->nullable()
                ->after('assigned_teacher_id')
                ->constrained('modules')
                ->nullOnDelete()
                ->comment('Prerequisite module that must be completed before enrolling');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->dropForeign(['prerequisite_module_id']);
            $table->dropColumn('prerequisite_module_id');
        });
    }
};
