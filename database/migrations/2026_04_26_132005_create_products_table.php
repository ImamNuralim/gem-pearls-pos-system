<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique();
            $table->enum('category', ['perhiasan', 'oleh-oleh']);
            $table->string('jewelry_type')->nullable(); // ATG, BRS, CCN, dst
            $table->string('price_tier')->nullable();   // A, B, C, D, E, F, G
            $table->decimal('price', 12, 2);
            $table->integer('stock')->default(0);
            $table->integer('low_stock_threshold')->default(3);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
