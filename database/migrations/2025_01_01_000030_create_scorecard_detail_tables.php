<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('match_player_stats', function (Blueprint $table) {
            $table->foreignId('fielder_id')->nullable()->after('player_id');
            $table->foreignId('bowler_id')->nullable()->after('fielder_id');
        });

        Schema::create('match_fall_of_wickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id');
            $table->string('side', 10)->default('rcb');
            $table->foreignId('player_id')->nullable();
            $table->string('name')->nullable();
            $table->string('score', 20)->nullable();
            $table->unsignedInteger('wicket_no')->nullable();
            $table->string('over', 20)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['match_id', 'side']);
        });

        Schema::create('match_commentaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id');
            $table->string('side', 10)->default('rcb');
            $table->string('over_number', 20)->nullable();
            $table->string('score_at', 20)->nullable();
            $table->string('ball_number', 20)->nullable();
            $table->string('ball_per_run')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['match_id', 'side']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('match_commentaries');
        Schema::dropIfExists('match_fall_of_wickets');

        Schema::table('match_player_stats', function (Blueprint $table) {
            $table->dropColumn(['fielder_id', 'bowler_id']);
        });
    }
};
