<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{

    protected $fillable = ['name', 'description', 'image'];

    public function drinks()
    {
        return $this->belongsToMany('App\Drink');
    }
}
