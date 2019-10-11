<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = [
        'user_id', 'phone_type', 'problem', 'pic_url','seasnail_id','reason'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
