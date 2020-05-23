<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Drink extends Model
{

    protected $fillable = [
        'name', 'description', 'added_by', 'instructions',
    ];

    // TODO get this to work
    // protected $casts = [
    //     'ingredients' => 'array',
    // ];

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function ingredients()
    {
        return $this->belongsToMany('App\Ingredient');
    }

    public function image()
    {
        return $this->hasOne('App\Image');
    }

    // this was for algolia search
    // public function searchableAs()
    // {
    //     return 'drinks';
    // }
}
