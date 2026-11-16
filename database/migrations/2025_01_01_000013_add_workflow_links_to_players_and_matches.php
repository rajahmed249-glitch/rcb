<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->foreignId('team_id')->nullable()->after('id');
        });

        Schema::table('matches', function (Blueprint $table) {
            $table->foreignId('tournament_id')->nullable()->after('match_number');
            $table->foreignId('opponent_team_id')->nullable()->after('opponent_name');
        });

        Schema::create('match_squad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id');
            $table->foreignId('player_id');
            $table->timestamps();

            $table->unique(['match_id', 'player_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('match_squad');

        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn(['tournament_id', 'opponent_team_id']);
        });

        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn('team_id');
        });
    }
};
