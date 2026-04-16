<?php
// FILE: database/migrations/2026_04_16_000004_add_department_and_lock_to_elections_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            // Department scoping
            $table->foreignId('department_id')
                  ->nullable()
                  ->after('description')
                  ->constrained('departments')
                  ->onDelete('set null');

            // Locking system
            $table->boolean('is_locked')->default(false)->after('status');
            $table->text('lock_reason')->nullable()->after('is_locked');
            $table->timestamp('locked_at')->nullable()->after('lock_reason');
        });
    }

    public function down(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn(['department_id', 'is_locked', 'lock_reason', 'locked_at']);
        });
    }
};