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
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->string('invoice_number')->unique();
        $table->foreignId('user_id')->constrained(); // kasir
        $table->enum('customer_type', ['walk_in', 'travel_agent', 'freelance_guide', 'member']);
        $table->string('customer_name')->nullable();
        $table->foreignId('partner_id')->nullable()->constrained()->nullOnDelete();
        $table->foreignId('member_id')->nullable()->constrained()->nullOnDelete();
        $table->decimal('subtotal', 12, 2);
        $table->integer('points_redeemed')->default(0);
        $table->decimal('points_discount', 12, 2)->default(0);
        $table->decimal('total', 12, 2);
        $table->boolean('is_negotiated')->default(false);
        $table->enum('payment_method', ['cash', 'qris', 'card']);
        $table->string('currency_code')->default('IDR');
        $table->decimal('currency_rate', 12, 4)->default(1);
        $table->decimal('admin_fee', 12, 2)->default(0);
        $table->decimal('amount_paid', 12, 2);
        $table->decimal('change_amount', 12, 2)->default(0);
        $table->string('customer_phone')->nullable(); // untuk kirim struk WA
        $table->enum('status', ['completed', 'cancelled'])->default('completed');
        $table->timestamps();
        $table->softDeletes();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
