<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('developments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['loteamento', 'condominio'])->default('loteamento');
            $table->text('description')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->char('state', 2)->nullable();
            $table->string('zip_code', 9)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('featured_image')->nullable();
            $table->enum('status', ['draft', 'active', 'launching', 'inactive'])->default('draft')->index();
            $table->date('launch_date')->nullable();
            $table->text('internal_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('development_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code', 30);
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['development_id', 'code']);
        });

        Schema::create('lots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('development_id')->constrained()->cascadeOnDelete();
            $table->foreignId('block_id')->constrained()->cascadeOnDelete();
            $table->string('number', 30);
            $table->string('internal_code', 50)->nullable();
            $table->decimal('area', 10, 2)->nullable();
            $table->decimal('front', 8, 2)->nullable();
            $table->decimal('back', 8, 2)->nullable();
            $table->decimal('left_side', 8, 2)->nullable();
            $table->decimal('right_side', 8, 2)->nullable();
            $table->decimal('price', 14, 2)->nullable();
            $table->decimal('promotional_price', 14, 2)->nullable();
            $table->enum('status', ['available', 'reserved', 'sold', 'blocked'])->default('available')->index();
            $table->string('map_identifier', 100)->nullable()->index();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['development_id', 'block_id', 'number']);
            $table->index(['development_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lots');
        Schema::dropIfExists('blocks');
        Schema::dropIfExists('developments');
    }
};
