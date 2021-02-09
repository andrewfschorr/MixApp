<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/signup', 'AuthController@signup');
Route::post('/login',  'AuthController@login');

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/user', function(Request $request) {
        return $request->user();
    });

    Route::post('/drink',                    'DrinksController@add');
    Route::delete('/shelf/{id}',      'ShelfController@add');
    Route::post('/shelf/{id}',        'ShelfController@remove');
    Route::get('/me',                 'AuthController@getAuthUser');
});

Route::get('/ingredients',        'IngredientsController@getIngredients');
Route::get('/tags',               'TagsController@getAllTags');
Route::get('/tag/{id}',           'TagsController@getTagById');
Route::get('/drinks',             'DrinksController@getDrinks');
// TODO this route doesnt feel very restful
Route::get('/drink/{id}/related', 'DrinksController@getRelatedDrinks');
Route::get('/drink/{id}',         'DrinksController@getDrink');

Route::get('/login/facebook',           'Auth\LoginController@redirectToProvider');
Route::get('/login/facebook/callback',  'Auth\LoginController@handleProviderCallback');
Route::get('/fbaccesscode',             'Auth\LoginController@checkFbAccessCode');
