<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->foreignId('toss_winner_id')->nullable()->after('result_text');
            $table->string('toss_elected', 10)->nullable()->after('toss_winner_id');
            $table->foreignId('winner_team_id')->nullable()->after('toss_elected');
            $table->foreignId('player_of_match_id')->nullable()->after('winner_team_id');
        });
    }

    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn(['toss_winner_id', 'toss_elected', 'winner_team_id', 'player_of_match_id']);
        });
    }
};
