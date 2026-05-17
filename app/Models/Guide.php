<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guide extends Model
{
    protected $fillable = [

        'guide_code',

        'name',

        'phone',

        'address',

        'total_visits',

        'is_active',

    ];

    protected $casts = [

        'is_active' => 'boolean',

    ];

    protected static function booted()
    {
        static::creating(function ($guide) {

            $lastGuide = Guide::latest('id')->first();

            $number = $lastGuide
                ? ((int) str_replace('GEM-', '', $lastGuide->guide_code)) + 1
                : 1;

            $guide->guide_code =
                'GEM-' . str_pad($number, 3, '0', STR_PAD_LEFT);

        });
    }

    public function visits()
    {
        return $this->belongsToMany(
            PartnerVisit::class,
            'partner_visit_guides'
        );
    }
}
