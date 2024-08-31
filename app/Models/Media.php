<?php

// app/Models/Media.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    // Specify the table if it's not the plural of the model name
    protected $table = 'media';

    // Define the fillable fields to allow mass assignment
    protected $fillable = [
        'title',
        'description',
    ];

    // Optionally, you can define relationships if needed
    // Example:
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
}
