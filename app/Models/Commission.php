<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    protected $fillable = [
        'partner_id',
        'commission_date',
        'total_sales',
        'commission_rate',
        'commission_amount',
        'status',
        'paid_at'
    ];

    protected $casts = [
        'commission_date' => 'date',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'total_sales' => 'decimal:2',
        'paid_at' => 'date',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
