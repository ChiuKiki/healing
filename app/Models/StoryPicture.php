<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Storypicture extends Model
{
    protected $table = 'storypicture';

    protected $fillable = [
        'story_id', 'url'
    ];

    protected $hidden = [
        'updated_at', 'created_at'
    ];
}
