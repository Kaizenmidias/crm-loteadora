<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('lot_map_areas', 'size')) {
            Schema::table('lot_map_areas', function (Blueprint $table) {
                $table->decimal('size', 8, 4)->nullable()->after('y');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('lot_map_areas', 'size')) {
            Schema::table('lot_map_areas', function (Blueprint $table) {
                $table->dropColumn('size');
            });
        }
    }
};
