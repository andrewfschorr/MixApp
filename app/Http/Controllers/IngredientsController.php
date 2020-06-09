<?php

namespace App\Http\Controllers;

use App\Ingredient;
use Illuminate\Http\Request;

class IngredientsController extends Controller
{
    public function getIngredients(Request $request)
    {
        $queryString = $request->input('q');
        $ingredientsSearchQuery = $queryString . '%';
        // TODO this is probably not right to access the DB directly
        $ingredients = \DB::table('ingredients')
                ->where('name', 'like', $ingredientsSearchQuery)
                ->limit(4)
                ->get();

        return $ingredients->map(function($ingredient) {
            return [
                'id' => $ingredient->id,
                'name' => $ingredient->name,
                'description' => $ingredient->description,
                'image' => $ingredient->image,
            ];
        });
    }
}
