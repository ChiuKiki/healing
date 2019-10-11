<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealRecording extends Model
{
    protected $table = 'healrecording';

    protected $fillable = [
        'user_id', 'heal_id', 'url'
    ];

    protected $hidden = [
        'updated_at', 'created_at'
    ];
}
