<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('match_player_stats', function (Blueprint $table) {
            if (! Schema::hasColumn('match_player_stats', 'balls')) {
                $table->unsignedInteger('balls')->nullable()->after('overs');
            }
        });

        if (! Schema::hasTable('match_extras')) {
            Schema::create('match_extras', function (Blueprint $table) {
                $table->id();
                $table->foreignId('match_id');
                $table->string('side', 10)->default('rcb');
                $table->unsignedInteger('byes')->default(0);
                $table->unsignedInteger('leg_byes')->default(0);
                $table->unsignedInteger('wides')->default(0);
                $table->unsignedInteger('no_balls')->default(0);
                $table->unsignedInteger('total')->default(0);
                $table->timestamps();

                $table->index(['match_id', 'side']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('match_extras');

        Schema::table('match_player_stats', function (Blueprint $table) {
            if (Schema::hasColumn('match_player_stats', 'balls')) {
                $table->dropColumn('balls');
            }
        });
    }
};
