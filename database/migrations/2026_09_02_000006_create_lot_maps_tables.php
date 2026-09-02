<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lot_maps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('development_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('file_path')->nullable();
            $table->string('file_type', 20)->default('svg');
            $table->json('metadata')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('lot_map_areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lot_map_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lot_id')->constrained()->cascadeOnDelete();
            $table->string('identifier', 100);
            $table->text('coordinates')->nullable();
            $table->text('svg_path')->nullable();
            $table->json('polygon')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->unique(['lot_map_id', 'identifier']);
            $table->unique(['lot_map_id', 'lot_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lot_map_areas');
        Schema::dropIfExists('lot_maps');
    }
};
