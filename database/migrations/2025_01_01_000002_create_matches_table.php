<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('match_number')->nullable();
            $table->string('opponent_name');
            $table->string('opponent_short_name', 20)->nullable();
            $table->string('venue')->nullable();
            $table->dateTime('match_date');
            $table->enum('status', ['upcoming', 'completed', 'cancelled'])->default('upcoming');
            $table->string('format', 30)->default('T15');
            $table->string('rcb_score', 20)->nullable();
            $table->string('rcb_overs', 20)->nullable();
            $table->string('opponent_score', 20)->nullable();
            $table->string('opponent_overs', 20)->nullable();
            $table->string('result_text')->nullable();
            $table->boolean('won')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
