<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gallery_photos', function (Blueprint $table) {
            $table->string('type', 10)->default('photo')->after('sort_order');
            $table->string('video_code', 100)->nullable()->after('type');
            $table->string('thumbnail')->nullable()->after('video_code');
            $table->date('date')->nullable()->after('thumbnail');
            $table->string('image')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('gallery_photos', function (Blueprint $table) {
            $table->dropColumn(['type', 'video_code', 'thumbnail', 'date']);
        });
    }
};
