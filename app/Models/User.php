<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['first_name', 'last_name', 'login_name', 'password', 'role_id', 'is_active'];

    // Define the relationship with Role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
