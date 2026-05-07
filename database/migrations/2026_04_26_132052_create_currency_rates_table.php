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
    Schema::create('currency_rates', function (Blueprint $table) {
        $table->id();
        $table->string('currency_code', 10);
        $table->string('currency_name');
        $table->decimal('rate_to_idr', 12, 4); // 1 unit = x IDR
        $table->timestamp('fetched_at');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currency_rates');
    }
};
