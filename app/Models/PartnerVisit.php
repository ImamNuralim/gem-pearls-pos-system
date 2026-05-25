<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerVisit extends Model
{
    protected $fillable = [
    'partner_id',
    'visit_code',
    'visit_type',
    'visit_type_label',
    'sticker_number',
    'group_description',
    'visit_date',
    'pickup_deadline',
    'vehicle_notes',
    'vehicle_description',
    'visitor_nationality',
    'tour_leader_name',
    'tour_leader_phone',
    'status',
    'total_sales'
];

    protected $casts = [
        'visit_date' => 'date',
        'pickup_deadline' => 'date',
    ];

    public static function generateVisitCode(): string
    {
        $last = self::orderBy('id', 'desc')->first();
        $nextNumber = $last ? (intval(substr($last->visit_code, 4)) + 1) : 1;
        return 'VIS-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function vehicles()
    {
        return $this->hasMany(CommissionVehicle::class, 'partner_visit_id');
    }

    public function transactions()
    {
        return $this->hasMany(\App\Models\Transaction::class);
    }

    public function guides()
    {
        return $this->belongsToMany(Guide::class, 'partner_visit_guides');
    }
    public function commissions()
    {
        return $this->hasMany(\App\Models\Commission::class, 'partner_visit_id');
    }

    public function drivers()
{
    return $this->belongsToMany(Driver::class, 'partner_visit_driver');
}
}
