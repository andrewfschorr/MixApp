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

    public function getIngredientsAttribute() {
        if ($this->ingredients()) {
            return $this->ingredients()->get()->map(function($i) {
                return [
                    'id' => $i->id,
                    'image' => $i->image,
                    'name' => $i->name,
                    'amount' => $i->pivot->amount,
                    'unit' => (int) $i->pivot->unit,
                ];
            });
        }
        return [];
    }

    public function ingredients()
    {
        return $this->belongsToMany('App\Ingredient')->withPivot('amount', 'unit');
    }

    public function image()
    {
        return $this->hasOne('App\DrinkImage');
    }

    public function getImageAttribute()
    {
        if ($this->image()->exists()) {
            return $this->image()->get()->first()->url;
        }
        return null;
    }

}
