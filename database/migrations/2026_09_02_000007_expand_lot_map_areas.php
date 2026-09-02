<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lot_map_areas', function (Blueprint $table) {
            $table->foreignId('lot_id')->nullable()->change();
            $table->string('type', 20)->default('lote')->after('lot_map_id');
            $table->string('label', 100)->after('type');
            $table->decimal('x', 8, 4)->nullable()->after('label');
            $table->decimal('y', 8, 4)->nullable()->after('x');
            $table->string('block_label', 100)->nullable()->after('y');
        });
    }

    public function down(): void
    {
        Schema::table('lot_map_areas', function (Blueprint $table) {
            $table->dropColumn(['type', 'label', 'x', 'y', 'block_label']);
        });
    }
};
