<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'name', 'phone', 'email',
        'points_balance', 'registered_at', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'registered_at' => 'date',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function pointLogs()
    {
        return $this->hasMany(MemberPoint::class);
    }
}
