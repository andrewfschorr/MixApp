<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DrinkImage extends Model
{
    protected $fillable = [
        'filename', 'url', 'drink_id',
    ];

    public function drink()
    {
        return $this->hasOne('App\Drink');
    }
}
