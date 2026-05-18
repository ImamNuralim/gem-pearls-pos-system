<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
    'invoice_number', 'user_id', 'customer_type',
    'customer_name', 'partner_id', 'partner_visit_id', 'member_id',
    'subtotal', 'points_redeemed', 'points_discount',
    'total', 'is_negotiated', 'payment_method',
    'currency_code', 'currency_rate', 'admin_fee',
    'amount_paid', 'change_amount', 'customer_phone', 'status', 'is_printed', 'receipt_token'
];

    protected $casts = [
        'is_negotiated' => 'boolean',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'points_discount' => 'decimal:2',
        'admin_fee' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'change_amount' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    // Generate invoice number
    public static function generateInvoiceNumber(): string
    {
        $prefix = 'GP-' . date('Ymd');
        $last = self::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $last ? (intval(substr($last->invoice_number, -4)) + 1) : 1;
        return $prefix . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function getTotalFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }
    public function visit()
{
    return $this->belongsTo(\App\Models\PartnerVisit::class, 'partner_visit_id');
}
}
