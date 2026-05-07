<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('commissions', function (Blueprint $table) {

    // ✅ kolom baru
    $table->date('commission_date')
          ->nullable()
          ->after('partner_id');

    $table->decimal('total_sales', 12, 2)
          ->default(0)
          ->after('commission_date');
});
}

public function down(): void
{
    Schema::table('commissions', function (Blueprint $table) {

        // rollback
        $table->foreignId('transaction_id')
              ->constrained()
              ->cascadeOnDelete();

        $table->decimal('transaction_total', 12, 2);

        $table->dropColumn('commission_date');
        $table->dropColumn('total_sales');
    });
}
};
