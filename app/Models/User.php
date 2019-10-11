<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';

    public static $userCanModify = [
        'name', 
    ];

    protected $fillable = [
        'name', 'sex', 'avatar', 'avatar_hash'
    ];

    protected $hidden = [
        'openid', 'updated_at', 'created_at', 'avatar_hash',
    ];
}
