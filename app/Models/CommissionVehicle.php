<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionVehicle extends Model
{
    protected $fillable = [

        'commission_id',

        'partner_visit_id',

        'plate_number',

        'vehicle_type',

    ];

    public function commission()
    {
        return $this->belongsTo(Commission::class);
    }

    public function visit()
    {
        return $this->belongsTo(PartnerVisit::class, 'partner_visit_id');
    }
}
