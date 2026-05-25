<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        \DB::statement("ALTER TABLE transactions MODIFY COLUMN payment_method ENUM('cash','qris','qris_bni','qris_mandiri','card','card_bca','card_mandiri','card_bri','card_bni') NOT NULL DEFAULT 'cash'");
    }

    public function down(): void
    {
        \DB::statement("ALTER TABLE transactions MODIFY COLUMN payment_method ENUM('cash','qris','card') NOT NULL DEFAULT 'cash'");
    }
};
