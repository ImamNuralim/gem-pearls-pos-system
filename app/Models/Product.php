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
    public static function generateSku($category, $jewelryType = null, $priceTier = null): string
    {
        if ($category === 'perhiasan') {
            // Format: PER-[JENIS]-[TIER]-[ID]
            $prefix = "PER-{$jewelryType}-{$priceTier}";
            $lastProduct = self::withTrashed()
                ->where('category', 'perhiasan')
                ->where('jewelry_type', $jewelryType)
                ->where('price_tier', $priceTier)
                ->orderBy('id', 'desc')
                ->first();

            $nextId = $lastProduct ? ($lastProduct->id + 1) : 1;
            return "{$prefix}-" . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        } else {
            // Format: OL-LOP-[ID]
            $lastProduct = self::withTrashed()
                ->where('category', 'oleh-oleh')
                ->orderBy('id', 'desc')
                ->first();

            $nextId = $lastProduct ? ($lastProduct->id + 1) : 1;
            return "OL-LOP-" . str_pad($nextId, 4, '0', STR_PAD_LEFT);
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
