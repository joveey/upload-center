<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mapping_indices', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('description');
            $table->string('table_name');
            $table->unsignedInteger('header_row');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mapping_indices');
    }
};