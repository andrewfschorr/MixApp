<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;

class TagsController extends Controller
{
    public function getAllTags(Request $request)
    {
        return Tag::all()->map(function($tag) {
            return $tag->only(['id', 'name']);
        });
    }

    public function getTagById(Request $request, $id)
    {
        return Tag::find($id)->drinks->map(function($t) {

            // return $t;
            return $t->only(['name', 'id', 'image']);
        });
    }
}
