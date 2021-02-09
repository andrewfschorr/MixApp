<?php

namespace App\Http\Controllers;

use App\Ingredient;
use App\Tag;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    /*
    *
    *
    * THIS HAS BEEN DEPRECATED IN FAVOR OF NOVA
    *
    */
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

    public function tags(Request $request)
    {
        return view('tags', [
            'bootstrapData' => Tag::all('id', 'name'),
        ]);
    }

    public function addTag(Request $request)
    {
        try {
            $ingredient = Tag::create([
                'name' => $request->input('name')
            ]);
            $ingredient->save();
        } catch (\Exception $e) {
            \Log::debug($e);
            return response()->json(['error' => 'Something went wrong'], 500);
        }
        return response()->json([
            'status' => 'OK',
            'tagAdded' => $ingredient->only('id', 'name'),
        ], 200);
    }

    public function updateTag(Request $request, $id)
    {
        $tag = Tag::find($id);
        $tag->name = $request->input('name');
        try {
            $tag->save();
        } catch (\Exception $e) {
            \Log::debug($e);
            return response()->json(['error' => 'Something went wrong'], 500);
        }
        return response()->json([
            'status' => 'OK',
            'tagUpdated' => $tag->only('id', 'name'),
        ], 200);

    }

    public function deleteTag(Request $request, $id)
    {
        $tag = Tag::find($id);
        try {
            $tag->delete();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
        return response()->json([
            'status' => 'OK',
            'tagDeleted' => (int)$id,
        ], 200);
    }
}
