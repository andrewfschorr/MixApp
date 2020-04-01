<?php

namespace App\Http\Controllers;

use App\Ingredient;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function ingredients(Request $request)
    {
        return view('ingredients', [
            'bootstrapData' => Ingredient::all('id', 'name', 'description', 'image'),
        ]);
    }
}
