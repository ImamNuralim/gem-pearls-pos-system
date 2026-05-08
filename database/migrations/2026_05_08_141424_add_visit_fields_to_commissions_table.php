<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commissions', function (Blueprint $table) {

            // No stiker group
            $table->string('sticker_number')
                  ->nullable()
                  ->after('partner_id');

            // Tanggal kunjungan
            $table->date('visit_date')
                  ->nullable()
                  ->after('sticker_number');

            // Batas pengambilan komisi
            $table->date('pickup_deadline')
                  ->nullable()
                  ->after('visit_date');

            // Catatan umum kendaraan
            $table->text('vehicle_notes')
                  ->nullable()
                  ->after('pickup_deadline');
        });
    }

    public function down(): void
    {
        Schema::table('commissions', function (Blueprint $table) {

            $table->dropColumn([
                'sticker_number',
                'visit_date',
                'pickup_deadline',
                'vehicle_notes'
            ]);
        });
    }
};
