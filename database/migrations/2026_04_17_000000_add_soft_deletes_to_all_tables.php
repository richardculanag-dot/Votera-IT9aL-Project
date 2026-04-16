<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('positions', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('candidates', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('votes', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('positions', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('candidates', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('votes', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};