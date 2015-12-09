<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['prefix' => 'api'], function(){
	
	Route::group(['prefix' => 'pav'], function(){
		
		Route::get('', ['uses' => 'HeroController@allHeroes']);

		Route::get('{id}', ['uses' => 'HeroController@getHero']);

		Route::post('', ['uses' => 'HeroController@saveHero']);

		Route::put('{id}', ['uses' => 'HeroController@updateHero']);

		Route::delete('{id}', ['uses' => 'HeroController@deleteHero']);

	});
});



Route::get('/', function () {
    return view('welcome');
    //return "Dota 2015";
});
