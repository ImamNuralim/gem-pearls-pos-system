<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('commissions', function (Blueprint $table) {
        $table->string('taken_by')->nullable()->after('status');
        $table->timestamp('taken_at')->nullable()->after('taken_by');
    });
}

public function down(): void
{
    Schema::table('commissions', function (Blueprint $table) {
        $table->dropColumn(['taken_by', 'taken_at']);
    });
}
};
