<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberPoint extends Model
{
    protected $fillable = [
        'member_id', 'transaction_id', 'type', 'points', 'note'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
