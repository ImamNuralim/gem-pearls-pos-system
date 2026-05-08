<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerVisit extends Model
{
    protected $fillable = [

        'partner_id',

        'visit_code',

        'sticker_number',

        'group_description',

        'visit_date',

        'pickup_deadline',

        'vehicle_notes',

        'status',

    ];

    protected $casts = [

        'visit_date' => 'date',

        'pickup_deadline' => 'date',

    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function vehicles()
    {
        return $this->hasMany(CommissionVehicle::class);
    }
    public function transactions()
{
    return $this->hasMany(\App\Models\Transaction::class);
}
}
