<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Buat tabel password_admin.
     */
    public function up(): void
    {
        Schema::create('password_admin', function (Blueprint $table) {
            $table->id();
            $table->string('password'); // disimpan dalam bentuk hash (bcrypt)
            $table->timestamps();
        });
    }

    /**
     * Rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_admin');
    }
};
