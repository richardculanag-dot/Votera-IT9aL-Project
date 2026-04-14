<?php
// FILE: database/migrations/2026_04_15_000002_create_elections_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('elections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['pending', 'ongoing', 'ended'])->default('pending');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes(); // soft delete for data integrity
        });

        // Migrate existing voting_settings status into elections
        // Run AFTER seeding an election manually or via seeder
    }

    public function down(): void
    {
        Schema::dropIfExists('elections');
    }
};