<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('tax_reports', function (Blueprint $table) {
        $table->id();
        $table->integer('year');
        $table->integer('month');
        $table->date('period_start');
        $table->date('period_end');
        $table->decimal('total_sales', 15, 2)->default(0);
        $table->decimal('commission_rate', 5, 2)->default(0);
        $table->decimal('commission_amount', 15, 2)->default(0);
        $table->decimal('sales_final', 15, 2)->default(0);
        $table->decimal('tax_amount', 15, 2)->default(0);
        $table->string('notes')->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('tax_reports');
}
};
