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
    Schema::create('guests', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained(); // security yang input
        $table->foreignId('partner_id')->nullable()->constrained()->nullOnDelete();
        $table->string('name');
        $table->string('origin')->nullable();        // asal
        $table->string('destination')->nullable();   // tujuan
        $table->timestamp('check_in')->nullable();
        $table->timestamp('check_out')->nullable();
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
