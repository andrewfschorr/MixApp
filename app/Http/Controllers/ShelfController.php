<?php

namespace App\Http\Controllers;

use App\Drink;
use Illuminate\Http\Request;

class ShelfController extends Controller
{
    public function add(Request $request, $id)
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

    public function remove(Request $request, $id)
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
