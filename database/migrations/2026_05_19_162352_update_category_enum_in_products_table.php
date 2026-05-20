<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    \DB::statement("ALTER TABLE products MODIFY COLUMN category ENUM('perhiasan','oleh-oleh','butiran') NOT NULL");
}

public function down(): void
{
    \DB::statement("ALTER TABLE products MODIFY COLUMN category ENUM('perhiasan','oleh-oleh') NOT NULL");
}
};
