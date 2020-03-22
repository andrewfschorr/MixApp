<?php

namespace App\Http\Controllers;

use App\Drink;
use Illuminate\Http\Request;

class DrinksController extends Controller
{
    public function add(Request $request)
    {
        try {
            $userId = \Auth::user()->id;
            $drink = Drink::create([
                'name' => $request->name,
                'description' => $request->description,
                'added_by' => $userId,
            ]);
        } catch (\Exception $e) {
            $errorCode = $e->errorInfo[1];
            if($errorCode == 1062){
                return response()->json(['error' => 'Duplicate Entry'], 409);
            }
            return response()->json(['error' => 'Something went wrong'], 500);
        }
        return response()->json(['drink' => $drink], 200);
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

    public function unMatchDrink(Request $request, $id)
    {
        try {
            \Auth::user()->drinks()->detach($id);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
        return response()->json([
            'status' => 'OK',
            'drinkRemoved' => (int)$id,
        ], 200);
    }

    public function matchDrink(Request $request, $id)
    {
        try {
            $thing = \Auth::user()->drinks()->attach($id);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
        return response()->json([
            'status' => 'OK',
            'drink' => \App\Drink::find((int)$id)->only([
                'id', 'name', 'description', 'image'
            ]),
        ], 200);
    }
}
