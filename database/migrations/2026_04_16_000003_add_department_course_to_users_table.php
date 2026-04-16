<?php
// FILE: database/migrations/2026_04_16_000003_add_department_course_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('department_id')
                  ->nullable()
                  ->after('student_id')
                  ->constrained('departments')
                  ->onDelete('set null');

            $table->foreignId('course_id')
                  ->nullable()
                  ->after('department_id')
                  ->constrained('courses')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['course_id']);
            $table->dropColumn(['department_id', 'course_id']);
        });
    }
};