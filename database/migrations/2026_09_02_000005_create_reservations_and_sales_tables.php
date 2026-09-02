<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('broker_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('lot_id')->constrained()->cascadeOnDelete();
            $table->foreignId('development_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reserved_at');
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->enum('status', ['pending', 'approved', 'cancelled', 'expired', 'converted'])->default('pending')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['lot_id', 'status']);
        });

        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('broker_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('lot_id')->constrained()->cascadeOnDelete();
            $table->foreignId('development_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reservation_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('value', 14, 2);
            $table->date('sold_at')->index();
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
        Schema::dropIfExists('reservations');
    }
};
