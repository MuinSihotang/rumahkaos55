<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // Menggunakan tabel users bawaan Laravel sebagai data Pelanggan
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); 
            $table->string('order_number')->unique(); // Contoh: INV-20260524-001
            $table->enum('status', ['pending', 'processing', 'shipped', 'completed', 'cancelled'])->default('pending');
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->text('shipping_address');
            $table->string('tracking_number')->nullable(); // Resi pengiriman
            $table->timestamps();
        });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
