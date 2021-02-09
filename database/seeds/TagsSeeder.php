<?php

use Illuminate\Database\Seeder;

class TagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Tag::class, 10)->create();

        App\Tag::all()->each(function($tag){
            App\Drink::all()->random()->tags()->attach($tag);
        });

    }
}
