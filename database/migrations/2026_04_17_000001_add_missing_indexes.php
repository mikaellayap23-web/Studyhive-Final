<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addIndexIfNotExists('enrollments', 'user_id');
        $this->addIndexIfNotExists('module_progress', 'user_id');
        $this->addIndexIfNotExists('announcement_reads', 'user_id');
        $this->addIndexIfNotExists('assessment_submission', 'user_id');
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('module_progress', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('announcement_reads', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('assessment_submission', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });
    }

    private function addIndexIfNotExists(string $table, string $column): void
    {
        $indexName = "{$table}_{$column}_index";
        $sm = Schema::getConnection()->getDoctrineSchemaManager();

        if (!collect(Schema::getIndexListing($table))->contains($indexName)) {
            Schema::table($table, function (Blueprint $table) use ($column) {
                $table->index($column);
            });
        }
    }
};
