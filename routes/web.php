<?php
// require __DIR__ . '/vendor/autoload.php';
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes([
    'register' => false,
]);

Route::get('/home',        'HomeController@index')->name('home');

Route::get('/ingredients',        'AdminController@ingredients');
Route::post('/ingredient',        'AdminController@addIngredient');
Route::delete('/ingredient/{id}', 'AdminController@deleteIngredient');
Route::put('/ingredient/{id}',    'AdminController@updateIngredient');

Route::get('/tags',        'AdminController@tags');
Route::post('/tag',        'AdminController@addTag');
Route::delete('/tag/{id}', 'AdminController@deleteTag');
Route::put('/tag/{id}',    'AdminController@updateTag');

Route::get('/home', 'HomeController@index')->name('home');
