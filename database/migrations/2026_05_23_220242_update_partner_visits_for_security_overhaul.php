<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('partner_visits', function (Blueprint $table) {
        $table->string('visitor_nationality')->nullable()->after('group_description'); // WNA/WNI
        $table->string('tour_leader_name')->nullable()->after('visitor_nationality');
        $table->string('tour_leader_phone')->nullable()->after('tour_leader_name');
        $table->string('visit_type_label')->nullable()->after('tour_leader_phone'); // travel_agent/freelance/no_guide
    });
}

public function down(): void
{
    Schema::table('partner_visits', function (Blueprint $table) {
        $table->dropColumn(['visitor_nationality', 'tour_leader_name', 'tour_leader_phone', 'visit_type_label']);
    });
}
};
