<?php

namespace App\Http\Controllers;

use App\Drink;
use App\Ingredient;
use App\Image;
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
                'added_by' => $userId,
                'instructions' => json_encode($instructions),
                // 'image' => $image->id,
            ]);

            // image storage
            if ($request->file('image')) {
                $path = $request->file('image')->store('images', 's3');
                \Storage::disk('s3')->setVisibility($path, 'public');
                $image = Image::create([
                    'filename' => basename($path),
                    'url' => \Storage::disk('s3')->url($path),
                    // 'drink_id' => $drink->id, attach below instead of this line
                ]);
                $drink->image()->save($image);
            }


            // same here
            $reqIngredients = is_array($request->ingredients)
                ? $request->ingredients : json_decode($request->ingredients, true);
            foreach ($reqIngredients as $i) {
                $ingredient = Ingredient::firstOrCreate(['name' => $i['name']]);
                $drink->ingredients()->attach($ingredient, [
                    'unit' => $i['unit'],
                    'amount' => $i['amount'],
                ]);
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
        $drinkSearchQuery = $queryString . '%';
        $drinks = \DB::table('drinks')
                ->where('name', 'like', $drinkSearchQuery)
                ->limit(4)
                ->get();

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
            'image' => $drink->image,
            'glass_type' => $drink->glass_type,
            'added_by' => $drink->added_by,
            'approved_status' => $drink->approved_status,
            'ingredients' => $drink->ingredients->map->only([
                'id', 'name', 'description', 'approved_status', 'image',
            ]),
            'instructions' => json_decode($drink->instructions),
            'image' => $drink->image->url,
        ];
    }
}
