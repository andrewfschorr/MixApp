<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Drink;
use App\User;
use App\Tag;
use Faker\Generator as Faker;

$factory->define(Drink::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
        'description' => Str::random(3),
        'user_id' => User::all()->random()->id,
    ];
});
