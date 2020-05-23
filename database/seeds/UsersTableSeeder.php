<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'a',
            'email' => 'a',
            'isAdmin' => 1,
            'password' => bcrypt('a'),
        ]);

        factory(App\User::class, 50)->create()->each(function ($user) {
            $drink = factory(App\Drink::class)->make();
            $user->drinks()->save($drink);

            $drink->ingredients()->save(factory(App\Ingredient::class)->make(), [
                'amount' => 1,
                'unit' => 1,
            ]);
        });
    }
}
