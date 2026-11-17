<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('player_applications', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('id');
            $table->date('dob')->nullable()->after('phone');
            $table->string('jersey_size', 20)->nullable()->after('preferred_role');
            $table->string('present_village')->nullable()->after('message');
            $table->unsignedBigInteger('present_district_id')->nullable()->after('present_village');
            $table->unsignedBigInteger('present_thana_id')->nullable()->after('present_district_id');
            $table->string('present_post_office')->nullable()->after('present_thana_id');
            $table->string('permanent_village')->nullable()->after('present_post_office');
            $table->unsignedBigInteger('permanent_district_id')->nullable()->after('permanent_village');
            $table->unsignedBigInteger('permanent_thana_id')->nullable()->after('permanent_district_id');
            $table->string('permanent_post_office')->nullable()->after('permanent_thana_id');
            $table->string('nationality', 50)->nullable()->after('permanent_post_office');
            $table->string('religion', 50)->nullable()->after('nationality');
            $table->string('blood_group', 10)->nullable()->after('religion');
            $table->string('height', 20)->nullable()->after('blood_group');
            $table->string('facebook_id')->nullable()->after('height');
        });
    }

    public function down(): void
    {
        Schema::table('player_applications', function (Blueprint $table) {
            $table->dropColumn([
                'photo', 'dob', 'jersey_size',
                'present_village', 'present_district_id', 'present_thana_id', 'present_post_office',
                'permanent_village', 'permanent_district_id', 'permanent_thana_id', 'permanent_post_office',
                'nationality', 'religion', 'blood_group', 'height', 'facebook_id',
            ]);
        });
    }
};
