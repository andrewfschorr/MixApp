<?php

namespace App\Http\Controllers;

use App\Ingredient;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function ingredients(Request $request)
    {
        return view('ingredients', [
            'bootstrapData' => Ingredient::all('id', 'name', 'description', 'image'),
        ]);
    }

    public function addIngredient(Request $request)
    {
        try {
            $ingredient = Ingredient::create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'image' => $request->input('image'),
            ]);
            $ingredient->save();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
        return response()->json([
            'status' => 'OK',
            'drinkAdded' => $ingredient->only('id', 'name', 'description', 'image'),
        ], 200);
    }

    public function updateIngredient(Request $request, $id)
    {
        $ingredient = Ingredient::find($id);
        $ingredient->name = $request->input('name');
        $ingredient->description = $request->input('description');
        try {
            $ingredient->save();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
        return response()->json([
            'status' => 'OK',
            'drinkUpdated' => $ingredient->only('id', 'name', 'description', 'image'),
        ], 200);

    }

    public function deleteIngredient(Request $request, $id)
    {
        $ingredient = Ingredient::find($id);
        try {
            $ingredient->delete();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
        return response()->json([
            'status' => 'OK',
            'drinkDeleted' => (int)$id,
        ], 200);
    }
}
