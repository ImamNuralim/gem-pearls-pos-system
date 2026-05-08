<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guides', function (Blueprint $table) {

            $table->id();

            $table->string('name');

            $table->string('phone')
                  ->nullable();

            $table->text('address')
                  ->nullable();

            // Berapa kali bawa wisatawan
            $table->integer('total_visits')
                  ->default(0);

            $table->boolean('is_active')
                  ->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guides');
    }
};
