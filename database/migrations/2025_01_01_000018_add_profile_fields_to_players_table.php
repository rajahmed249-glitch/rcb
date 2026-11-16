<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->unsignedBigInteger('district_id')->nullable()->after('role_icon_id');
            $table->unsignedBigInteger('thana_id')->nullable()->after('district_id');
            $table->string('village')->nullable()->after('thana_id');
            $table->string('post_office')->nullable()->after('village');
            $table->string('religion')->nullable()->after('post_office');
            $table->string('blood_group')->nullable()->after('religion');
            $table->string('nationality')->nullable()->after('blood_group');
            $table->date('debut')->nullable()->after('nationality');
            $table->string('player_tag')->nullable()->after('debut');
            $table->boolean('status')->default(true)->after('player_tag');
        });
    }

    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn([
                'district_id',
                'thana_id',
                'village',
                'post_office',
                'religion',
                'blood_group',
                'nationality',
                'debut',
                'player_tag',
                'status',
            ]);
        });
    }
};
