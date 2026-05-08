<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commission_vehicles', function (Blueprint $table) {

            $table->id();

            $table->foreignId('commission_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Plat kendaraan
            $table->string('plate_number');

            // Keterangan kendaraan
            // contoh:
            // Bus Pariwisata
            // Hiace Premio
            $table->string('vehicle_type')
                  ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commission_vehicles');
    }
};
