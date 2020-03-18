<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Drink extends Model
{

    protected $fillable = [
        'name', 'description', 'addedBy',
    ];

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    // this was for algolia search
    // public function searchableAs()
    // {
    //     return 'drinks';
    // }
}
