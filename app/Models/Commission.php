<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    protected $fillable = [
        'partner_id',
        'partner_visit_id',
        'sticker_number',
        'group_description',
        'visit_date',
        'pickup_deadline',
        'vehicle_notes',
        'commission_date',
        'total_sales',
        'commission_rate',
        'commission_amount',
        'status',
        'paid_at', 'taken_by', 'taken_at'
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'paid_at' => 'date',
        'visit_date' => 'date',
        'pickup_deadline' => 'date',
        'commission_date' => 'date',
    'taken_at'        => 'datetime'
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function visit()
    {
        return $this->belongsTo(PartnerVisit::class, 'partner_visit_id');
    }

    public function guides()
    {
        return $this->belongsToMany(Guide::class, 'commission_guides');
    }

    public function vehicles()
    {
        return $this->hasMany(CommissionVehicle::class);
    }
}
