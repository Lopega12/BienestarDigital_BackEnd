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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:api')->post('storeFromFile','FileController@post');
Route::middleware('auth:api')->post('store','FileController@post');

Route::post('loginApi','LoginUserController@login');
Route::post('registerApi','LoginUserController@register');

Route::post('/password/email','Api\ForgotPasswordController@sendResetLinkEmail');
Route::post('/password/reset','Api\ResetPasswordController@reset');
