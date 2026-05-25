<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = ['driver_code', 'name', 'phone', 'is_active'];

    protected static function booted()
    {
        static::creating(function ($model) {
            $last = self::orderBy('id', 'desc')->first();
            $number = $last ? (intval(substr($last->driver_code, 7)) + 1) : 1;
            $model->driver_code = 'DRIVER-' . str_pad($number, 3, '0', STR_PAD_LEFT);
        });
    }

    public function visits()
    {
        return $this->belongsToMany(PartnerVisit::class, 'partner_visit_driver');
    }
}
