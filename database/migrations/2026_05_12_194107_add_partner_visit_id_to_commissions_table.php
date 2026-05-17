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
    Schema::table('commissions', function (Blueprint $table) {
        $table->foreignId('partner_visit_id')->nullable()->after('partner_id')->constrained('partner_visits')->nullOnDelete();
    });
}

public function down(): void
{
    Schema::table('commissions', function (Blueprint $table) {
        $table->dropConstrainedForeignId('partner_visit_id');
    });
}
};
