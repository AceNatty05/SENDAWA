<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Rename tabel reviews → posts, kolom review_content → content.
     */
    public function up(): void
    {
        Schema::rename('reviews', 'posts');

        Schema::table('posts', function (Blueprint $table) {
            $table->renameColumn('review_content', 'content');
        });
    }

    /**
     * Rollback: kembalikan ke semula.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->renameColumn('content', 'review_content');
        });

        Schema::rename('posts', 'reviews');
    }
};
