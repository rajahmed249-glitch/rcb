<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matches_new', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('match_number')->nullable();
            $table->string('match_name')->nullable();
            $table->foreignId('tournament_id')->nullable();
            $table->string('opponent_name');
            $table->string('opponent_name_bn')->nullable();
            $table->foreignId('opponent_team_id')->nullable();
            $table->string('opponent_short_name', 20)->nullable();
            $table->string('opponent_logo')->nullable();
            $table->string('venue')->nullable();
            $table->dateTime('match_date');
            $table->string('match_time', 20)->nullable();
            $table->string('status')->default('upcoming');
            $table->string('format', 30)->default('T15');
            $table->string('rcb_score', 20)->nullable();
            $table->string('rcb_overs', 20)->nullable();
            $table->unsignedInteger('rcb_extras')->nullable();
            $table->string('opponent_score', 20)->nullable();
            $table->string('opponent_overs', 20)->nullable();
            $table->unsignedInteger('opponent_extras')->nullable();
            $table->string('result_text')->nullable();
            $table->boolean('won')->nullable();
            $table->text('match_info')->nullable();
            $table->timestamps();
        });

        DB::statement('INSERT INTO matches_new
            (id, match_number, match_name, tournament_id, opponent_name, opponent_name_bn, opponent_team_id, opponent_short_name, opponent_logo, venue, match_date, match_time, status, format, rcb_score, rcb_overs, rcb_extras, opponent_score, opponent_overs, opponent_extras, result_text, won, match_info, created_at, updated_at)
            SELECT id, match_number, NULL, tournament_id, opponent_name, opponent_name_bn, opponent_team_id, opponent_short_name, opponent_logo, venue, match_date, NULL, status, format, rcb_score, rcb_overs, rcb_extras, opponent_score, opponent_overs, opponent_extras, result_text, won, NULL, created_at, updated_at
            FROM matches');

        Schema::drop('matches');
        Schema::rename('matches_new', 'matches');
    }

    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn(['match_name', 'match_time', 'match_info']);
        });
    }
};
