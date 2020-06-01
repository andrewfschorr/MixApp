<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialProfile extends Model
{
    protected $fillable = [
        'provider', 'fb_id', 'user_id'
    ];
}
