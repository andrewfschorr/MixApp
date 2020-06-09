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
}
