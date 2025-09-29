<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mapping_columns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mapping_index_id')->constrained('mapping_indices')->onDelete('cascade');
            $table->string('excel_column_index');
            $table->string('table_column_name');
            $table->string('data_type')->default('string');
            $table->boolean('is_required')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mapping_columns');
    }
};