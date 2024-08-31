<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventUser extends Model
{
    protected $fillable = [
        'event_id', 'user_id'
    ];

    // Specify the table name explicitly
    protected $table = 'event_user';
}
