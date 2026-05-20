<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesStaff extends Model
{
    protected $fillable = ['name', 'code', 'team', 'is_active'];

    protected static function booted()
    {
        static::creating(function ($model) {
            $last = self::orderBy('id', 'desc')->first();
            $number = $last ? (intval(substr($last->code, 4)) + 1) : 1;
            $model->code = 'SLS-' . str_pad($number, 3, '0', STR_PAD_LEFT);
        });
    }

    public function transactions()
    {
        return $this->hasMany(\App\Models\Transaction::class);
    }
}
