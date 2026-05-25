<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('partner_visit_driver', function (Blueprint $table) {
        $table->id();
        $table->foreignId('partner_visit_id')->constrained()->cascadeOnDelete();
        $table->foreignId('driver_id')->constrained()->cascadeOnDelete();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('partner_visit_driver');
}
};
