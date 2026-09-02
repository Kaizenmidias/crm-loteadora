<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lot_map_areas', function (Blueprint $table) {
            if (!Schema::hasColumn('lot_map_areas', 'development_label')) $table->string('development_label', 150)->nullable();
            if (!Schema::hasColumn('lot_map_areas', 'address')) $table->string('address', 255)->nullable();
            if (!Schema::hasColumn('lot_map_areas', 'value')) $table->decimal('value', 14, 2)->nullable();
            if (!Schema::hasColumn('lot_map_areas', 'area')) $table->decimal('area', 10, 2)->nullable();
            if (!Schema::hasColumn('lot_map_areas', 'price_per_m2')) $table->decimal('price_per_m2', 14, 2)->nullable();
            if (!Schema::hasColumn('lot_map_areas', 'status')) $table->string('status', 30)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('lot_map_areas', function (Blueprint $table) {
            foreach (['development_label', 'address', 'value', 'area', 'price_per_m2', 'status'] as $column) {
                if (Schema::hasColumn('lot_map_areas', $column)) $table->dropColumn($column);
            }
        });
    }
};
