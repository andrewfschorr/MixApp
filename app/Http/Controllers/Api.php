<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Api extends Controller
{
    public function foo(Request $request)
    {
        return [
            'foo' => [
                'something' => 'something else',
                'idk' => 'idk man',
            ],
        ];
    }
}
