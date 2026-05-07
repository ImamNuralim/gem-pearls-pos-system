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
    Schema::create('commissions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('partner_id')->constrained()->cascadeOnDelete();
        $table->foreignId('transaction_id')->constrained()->cascadeOnDelete();
        $table->decimal('transaction_total', 12, 2);
        $table->decimal('commission_rate', 5, 2);
        $table->decimal('commission_amount', 12, 2);
        $table->enum('status', ['unpaid', 'paid'])->default('unpaid');
        $table->date('paid_at')->nullable();
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};
