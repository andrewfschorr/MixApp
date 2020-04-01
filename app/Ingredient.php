<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{

    protected $fillable = [];

    public function drinks()
    {
        return $this->belongsToMany('App\Drink');
    }
}
