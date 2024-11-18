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
        Schema::table('videos', function (Blueprint $table) {
            $table->json('video_formats')->nullable()->after('video_id');
            $table->string('destination_path')->nullable()->after('video_formats');
            $table->string('video_size')->nullable()->after('video_formats');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn(['video_formats', 'destination_path', 'video_size']);
        });
    }
};
