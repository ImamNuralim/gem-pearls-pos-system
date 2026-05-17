<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxReport extends Model
{
    protected $fillable = [
        'year', 'month', 'period_start', 'period_end',
        'total_sales', 'commission_rate', 'commission_amount',
        'sales_final', 'tax_amount', 'notes',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end'   => 'date',
        'total_sales'  => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'sales_final'  => 'decimal:2',
        'tax_amount'   => 'decimal:2',
    ];
}
