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

Route::post('login', 'Api\UserControllerUserController@login');
Route::post('register', 'Api\UserController@register');
Route::group(['middleware' => 'accesstoken'], function () {
    Route::get('profile', 'Api\UserController@profile');
    Route::get('logout', 'Api\UserController@logout');
    Route::delete('delete', 'Api\UserController@deleteProfile');
});
Route::get('confirm_phone', 'Api\UserController@confirm_phone');
