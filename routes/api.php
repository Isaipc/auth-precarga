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

//Gestiona el grupo de rutas para las sesiones de usuario
Route::group(['prefix' => 'auth'], function () {

    //Inicia la sesión
    Route::post('login', 'AuthController@login');
    
    // Registra un nuevo usuario
    Route::post('signup', 'AuthController@signup');

    Route::group(['middleware' => 'auth:api'], function () {

        //Termina la sesión
        Route::get('logout', 'AuthController@logout');

        //Obtiene los datos del usuario/alumno
        Route::get('user', 'AuthController@user');
    });
});

//Gestiona el grupo de rutas para las operaciones con las materias
Route::group(['middleware' => 'auth:api', 'prefix' => 'precarga'], function () {

    // Solicita las materias precargadas del alumno:
    Route::get('obtenerPrecarga', 'PrecargaController@obtenerPrecarga');

    // Solicita las materias disponibles para precarga:
    Route::get('obtenerMaterias', 'PrecargaController@obtenerMaterias');

    // Guardar la precarga con las materias seleccionadas:
    Route::post('guardar', 'PrecargaController@guardar');

    // Genera el documento de la precarga con todos los datos:
    Route::get('pdf', 'PdfController@generarPdf');
});
