<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('match_player_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id');
            $table->foreignId('player_id')->nullable();
            $table->string('side', 10)->default('rcb');
            $table->string('stat_type', 10)->default('batting');
            $table->string('name')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedInteger('runs')->nullable();
            $table->unsignedInteger('balls')->nullable();
            $table->unsignedInteger('fours')->nullable();
            $table->unsignedInteger('sixes')->nullable();
            $table->string('dismissal')->nullable();
            $table->string('overs', 20)->nullable();
            $table->unsignedInteger('maidens')->nullable();
            $table->unsignedInteger('wickets')->nullable();
            $table->unsignedInteger('wides')->nullable();
            $table->unsignedInteger('noballs')->nullable();
            $table->timestamps();

            $table->index(['match_id', 'side', 'stat_type']);
        });

        Schema::table('matches', function (Blueprint $table) {
            $table->unsignedInteger('rcb_extras')->nullable()->after('rcb_overs');
            $table->unsignedInteger('opponent_extras')->nullable()->after('opponent_overs');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('match_player_stats');

        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn(['rcb_extras', 'opponent_extras']);
        });
    }
};
