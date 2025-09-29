<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spotify_users', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable(); // Sesuaikan tipe data jika perlu
            $table->string('gender')->nullable();
            $table->integer('age')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spotify_users');
    }
};