<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $fallbackElectionId = DB::table('elections')->orderBy('id')->value('id');

        if ($fallbackElectionId !== null) {
            DB::table('positions')
                ->whereNull('election_id')
                ->update(['election_id' => $fallbackElectionId]);
        }

        Schema::table('positions', function (Blueprint $table) {
            $table->unique(['election_id', 'name'], 'positions_election_id_name_unique');
        });

        // Use SQL to avoid dependency on doctrine/dbal for column alteration.
        DB::statement('ALTER TABLE positions MODIFY election_id BIGINT UNSIGNED NOT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE positions MODIFY election_id BIGINT UNSIGNED NULL');

        Schema::table('positions', function (Blueprint $table) {
            $table->dropUnique('positions_election_id_name_unique');
        });
    }
};
