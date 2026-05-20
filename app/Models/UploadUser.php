<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UploadUser extends Model
{
    protected $fillable = ['name', 'email', 'password', 'is_active'];

    protected $hidden = ['password'];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function checkPassword(string $password): bool
    {
        return \Illuminate\Support\Facades\Hash::check($password, $this->password);
    }
}
