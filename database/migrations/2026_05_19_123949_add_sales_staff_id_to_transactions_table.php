<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('transactions', function (Blueprint $table) {
        $table->foreignId('sales_staff_id')->nullable()->after('user_id')->constrained('sales_staff')->nullOnDelete();
    });
}

public function down(): void
{
    Schema::table('transactions', function (Blueprint $table) {
        $table->dropConstrainedForeignId('sales_staff_id');
    });
}
};
