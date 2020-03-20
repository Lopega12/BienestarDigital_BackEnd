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

//Guardado y envio del fichero a base de datos//
Route::middleware('auth:api')->post('storeFromFile','FileController@post');
Route::middleware('auth:api')->post('store','FileController@post');
Route::middleware('auth:api')->post('appsload','FileController@insertApp');
//LOGIN Y RECUPERACION DE PASSWORD//
Route::post('loginApi','LoginUserController@login');
Route::post('registerApi','LoginUserController@register');

Route::post('/password/email','Api\ForgotPasswordController@sendResetLinkEmail');
Route::post('/password/reset','Api\ResetPasswordController@reset');

//Obtener apps guardads en base de datos//
Route::get('apps','AppController@getAllApps');

Route::middleware('auth:api')->post('create_restrinction/{app_id}','UserController@save_restriction');
Route::middleware('auth:api')->get('restrinctions','AppController@get_restrinctions');

Route::middleware('auth:api')->get('apps/stats','AppController@getStatsApps');

//Tiempo Total dias anteriores de app especifica
Route::middleware('auth:api')->get('apps/total_time_usage_day/{id}','AppController@getUseTimeAppPerDay');

//Tiempo de uso total//
Route::middleware('auth:api')->get('/usage_time/{id}','UserController@use_time_apps');

//Obtener coordenadas de las apps
Route::middleware('auth:api')->get('/location_apps','AppController@get_apps_location');



