<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Drink;
use App\User;
use Faker\Generator as Faker;

$factory->define(Drink::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
        'description' => Str::random(3),
        'added_by' => User::all()->random()->id,
    ];
});
