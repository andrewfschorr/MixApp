<?php

namespace App\Http\Controllers;

use App\Drink;
use App\Tag;
use App\Ingredient;
use App\DrinkImage;
use Illuminate\Http\Request;

class DrinksController extends Controller
{
    // TODO which routes need auth middleware
    public function add(Request $request)
    {
        try {
            $userId = \Auth::user()->id;
            // need to check if its an array (content-type: application/json) or
            // or if its a string, formData
            $reqInstructions = is_array($request->instructions)
                ? $request->instructions : json_decode($request->instructions);
            $instructions = [];
            foreach ($reqInstructions as $i) {
                $instructions[] = $i;
            }

            $drink = Drink::create([
                'name' => $request->name,
                'description' => $request->description,
                'user_id' => $userId,
                'instructions' => json_encode($instructions),
            ]);

            // image storage
            if ($request->file('image')) {
                $path = $request->file('image')->store('images', 's3');
                \Storage::disk('s3')->setVisibility($path, 'public');
                $image = DrinkImage::create([
                    'filename' => basename($path),
                    'url' => \Storage::disk('s3')->url($path),
                ]);
                $drink->image()->save($image);
            }

            $reqIngredients = is_array($request->ingredients)
                ? $request->ingredients : json_decode($request->ingredients, true);
            foreach ($reqIngredients as $i) {
                $ingredient = Ingredient::firstOrCreate(['name' => $i['name']]);
                $drink->ingredients()->attach($ingredient, [
                    'unit' => $i['unit'],
                    'amount' => $i['amount'],
                ]);
            }

            $tags = null;
            // if its content type form data we have to decode
            if ($request->tags) {
                if (\Str::startsWith($request->headers->get('Content-Type'), 'multipart/form-data;')) {
                    $tags = json_decode($request->tags, true);
                } else {
                    $tags = $request->tags;
                }
            }

            if (!is_null($tags) && is_array($tags)) {
                foreach ($tags as $t) {
                    $tag = Tag::find((int) $t);
                    $drink->tags()->attach($tag);
                }
            }

        } catch (\Exception $e) {
            \Log::error($e);
            $errorCode = $e->errorInfo[1];
            if($errorCode == 1062){
                return response()->json(['error' => 'Duplicate Entry'], 409);
            }
            return response()->json(['error' => 'Something went wrong'], 500);
        }
        return response()->json(['drink' => $drink->load('image')], 200);
    }

    public function getDrinks(Request $request)
    {
        $queryString = $request->input('q');
        $drinks = \App\Drink::where('name', 'like', $queryString ."%")->get();
        return $drinks->map(function($drink) {
            return [
                'id' => $drink->id,
                'name' => $drink->name,
                'description' => $drink->description,
                'image' => $drink->image,
            ];
        });
    }

    public function getDrink(Request $request, $id)
    {
        $drink = Drink::with(['ingredients'])->find($id);
        return [
            'name' => $drink->name,
            'id' => $drink->id,
            'description' => $drink->description,
            'glass_type' => $drink->glass_type,
            'user_id' => $drink->user_id,
            'approved_status' => $drink->approved_status,
            'ingredients' => $drink->ingredients,
            'instructions' => $drink->instructions ? json_decode($drink->instructions) : [],
            'image' => $drink->image,
            'tags' => $drink->tags->map(function($t) {
                return [
                    'name' => $t->name,
                    'id' => $t->id,
                ];
            }),
        ];
    }

    public function getRelatedDrinks(Request $request, $id)
    {
        $drink = Drink::find($id);
        $a = [];
        // TODO limit this at like 5 or 10 related drinks
        $drink->ingredients->map(function($ingredient) use (&$a){
            $ingredientId = $ingredient['id'];
            $drinks = Ingredient::find($ingredientId)->drinks;
            $a = array_merge($a, $drinks->toArray());
        });
        return [
            'relatedDrinks' => $a,
        ];
    }
}
