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
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('receiver_name');
            $table->string('phone_number');
            $table->text('full_address');
            $table->string('district'); // Kecamatan
            $table->string('city'); // Kota/Kabupaten
            $table->string('province'); // Provinsi
            $table->string('postal_code');
            $table->boolean('is_primary')->default(false); // Penanda alamat utama
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
