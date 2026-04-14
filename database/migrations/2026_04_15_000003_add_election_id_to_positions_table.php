<?php
// FILE: database/migrations/2026_04_15_000003_add_election_id_to_positions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->foreignId('election_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('elections')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->dropForeign(['election_id']);
            $table->dropColumn('election_id');
        });
    }
};