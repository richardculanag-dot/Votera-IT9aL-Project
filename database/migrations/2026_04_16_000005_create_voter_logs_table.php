<?php
// FILE: database/migrations/2026_04_16_000005_create_voter_logs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voter_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('election_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['completed', 'failed', 'partial'])->default('completed');
            $table->string('ip_address', 45)->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();

            // One log per student per election (latest wins via update or just insert)
            $table->index(['student_id', 'election_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voter_logs');
    }
};