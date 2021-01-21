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

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'precarga'], function () {
    
    // Solicita las materias precargadas del alumno:
    Route::get('solicitar', 'PrecargaController@solicitar');

    // Solicita las materias disponibles para precarga:
    Route::get('obtenerMaterias', 'PrecargaController@obtenerMaterias');

    // Guardar la precarga con las materias seleccionadas:
    Route::get('guardar', 'PrecargaController@guardar');
});

