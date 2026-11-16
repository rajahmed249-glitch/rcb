<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->string('name_bn')->nullable()->after('name');
            $table->text('bio_bn')->nullable()->after('bio');
        });

        Schema::table('matches', function (Blueprint $table) {
            $table->string('opponent_name_bn')->nullable()->after('opponent_name');
        });

        Schema::table('news_posts', function (Blueprint $table) {
            $table->string('title_bn')->nullable()->after('title');
            $table->text('excerpt_bn')->nullable()->after('excerpt');
            $table->longText('content_bn')->nullable()->after('content');
        });

        Schema::table('sponsors', function (Blueprint $table) {
            $table->string('name_bn')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn(['name_bn', 'bio_bn']);
        });

        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn('opponent_name_bn');
        });

        Schema::table('news_posts', function (Blueprint $table) {
            $table->dropColumn(['title_bn', 'excerpt_bn', 'content_bn']);
        });

        Schema::table('sponsors', function (Blueprint $table) {
            $table->dropColumn('name_bn');
        });
    }
};
