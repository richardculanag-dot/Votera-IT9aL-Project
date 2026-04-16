<?php
// FILE: database/migrations/2026_04_16_000006_create_election_student_eligibility_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('election_student_eligibility', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->boolean('allowed')->default(true);
            $table->text('reason')->nullable(); // optional note for disqualification
            $table->timestamps();

            $table->unique(['election_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('election_student_eligibility');
    }
};