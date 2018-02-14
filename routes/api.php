<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['middleware' => ['api']], function () {

    Route::post('register', 'APIController@register');

    Route::post('login', 'APIController@login');

    Route::group(['middleware' => 'jwt-auth'], function () {

        Route::post('pill', 'APIController@createPill');

        Route::delete('pill', 'APIController@deletePill');

        Route::patch('pill', 'APIController@updatePill');

        Route::put('order', 'APIController@updateOrder');

    });

});
