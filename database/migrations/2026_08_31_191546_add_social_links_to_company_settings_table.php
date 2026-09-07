<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->string('facebook_url', 255)->nullable()->after('website');
            $table->string('twitter_url', 255)->nullable()->after('facebook_url');
            $table->string('linkedin_url', 255)->nullable()->after('twitter_url');
            $table->string('instagram_url', 255)->nullable()->after('linkedin_url');
            $table->string('youtube_url', 255)->nullable()->after('instagram_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_settings', function (Blueprint $table) {
            $table->dropColumn([
                'facebook_url',
                'twitter_url',
                'linkedin_url',
                'instagram_url',
                'youtube_url',
            ]);
        });
    }
};
