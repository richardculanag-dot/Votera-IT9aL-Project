<?php
// FILE: database/migrations/2026_04_15_000004_add_election_id_to_votes_table.php
//
// NOTE on 3NF: votes.position_id is technically derivable from
// candidates.position_id (transitive dependency). It is kept here
// intentionally for query performance (avoids JOIN on every tally).
// This is an accepted denormalization trade-off.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->foreignId('election_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('elections')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropForeign(['election_id']);
            $table->dropColumn('election_id');
        });
    }
};