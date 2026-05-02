<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration — membuat tabel reviews.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('review_content');
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Batalkan migration — hapus tabel reviews.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
