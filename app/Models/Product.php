<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'sku', 'category', 'jewelry_type',
        'price_tier', 'price', 'stock',
        'low_stock_threshold', 'description', 'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relasi ke foto
    public function photos()
    {
        return $this->hasMany(ProductPhoto::class);
    }

    public function primaryPhoto()
    {
        return $this->hasOne(ProductPhoto::class)->where('is_primary', true);
    }

    // Relasi ke restock log
    public function restockLogs()
    {
        return $this->hasMany(RestockLog::class);
    }

    // Auto nonaktif kalau stok 0
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('stock', '>', 0);
    }

    // Generate SKU otomatis
    // Generate SKU otomatis
    public static function generateSku($category, $jewelryType = null, $priceTier = null): string
    {
        if ($category === 'perhiasan') {
            $prefix = "PER-{$jewelryType}-{$priceTier}";

            $lastProduct = self::withTrashed()
                ->where('sku', 'like', "{$prefix}-%")
                ->orderByRaw('CAST(SUBSTRING_INDEX(sku, "-", -1) AS UNSIGNED) DESC')
                ->first();

            if ($lastProduct) {
                $lastNumber = intval(substr($lastProduct->sku, strrpos($lastProduct->sku, '-') + 1));
                $nextId = $lastNumber + 1;
            } else {
                $nextId = 1;
            }

            $sku = "{$prefix}-" . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            while (self::withTrashed()->where('sku', $sku)->exists()) {
                $nextId++;
                $sku = "{$prefix}-" . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }

            return $sku;

        } else {
            $lastProduct = self::withTrashed()
                ->where('sku', 'like', "OL-LOP-%")
                ->orderByRaw('CAST(SUBSTRING_INDEX(sku, "-", -1) AS UNSIGNED) DESC')
                ->first();

            if ($lastProduct) {
                $lastNumber = intval(substr($lastProduct->sku, strrpos($lastProduct->sku, '-') + 1));
                $nextId = $lastNumber + 1;
            } else {
                $nextId = 1;
            }

            $sku = "OL-LOP-" . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            while (self::withTrashed()->where('sku', $sku)->exists()) {
                $nextId++;
                $sku = "OL-LOP-" . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }

            return $sku;
        }
    }

    // Format harga
    public function getPriceFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    // Status stok
    public function getStockStatusAttribute(): string
    {
        if ($this->stock === 0) return 'habis';
        if ($this->stock <= $this->low_stock_threshold) return 'menipis';
        return 'aman';
    }
}
