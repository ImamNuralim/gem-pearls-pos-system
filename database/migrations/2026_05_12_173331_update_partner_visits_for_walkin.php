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
    Schema::table('partner_visits', function (Blueprint $table) {
        $table->dropForeign(['partner_id']);
        $table->foreignId('partner_id')->nullable()->change();
        $table->foreign('partner_id')->references('id')->on('partners')->nullOnDelete();
        $table->enum('visit_type', ['partner', 'walk_in'])->default('partner')->after('partner_id');
        $table->string('vehicle_description')->nullable()->after('vehicle_notes');
    });
}

public function down(): void
{
    Schema::table('partner_visits', function (Blueprint $table) {
        $table->dropForeign(['partner_id']);
        $table->foreignId('partner_id')->change();
        $table->foreign('partner_id')->references('id')->on('partners')->cascadeOnDelete();
        $table->dropColumn(['visit_type', 'vehicle_description']);
    });
}
};
