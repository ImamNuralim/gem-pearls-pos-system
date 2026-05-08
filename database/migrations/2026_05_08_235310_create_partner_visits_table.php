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
    Schema::create('partner_visits', function (Blueprint $table) {

        $table->id();

        $table->foreignId('partner_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->string('visit_code')->unique();

        $table->string('sticker_number')->nullable();

        $table->text('group_description')->nullable();

        $table->date('visit_date');

        $table->date('pickup_deadline')->nullable();

        $table->longText('vehicle_notes')->nullable();

        $table->enum('status', [
            'pending',
            'shopping',
            'completed'
        ])->default('pending');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::dropIfExists('partner_visits');
}
};
