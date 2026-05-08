<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commission_guides', function (Blueprint $table) {

            $table->id();

            $table->foreignId('commission_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('guide_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commission_guides');
    }
};
