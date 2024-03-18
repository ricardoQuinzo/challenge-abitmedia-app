<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// Controlador para la autentificación
use App\Http\Controllers\AuthController;
// Controlador de Usuarios
use App\Http\Controllers\UserController;
// Controlador para Software
use App\Http\Controllers\SoftwareController;
//Controlador para Servicios
use App\Http\Controllers\ServicesController;

// Routas para la  Autenticación con prefijo (auth) en la url

Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {   
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);  
    Route::get('/user-profile', [AuthController::class, 'userProfile']);  
});

// Routas del Sistema agregadas a la autentificacion configurada en auth.php

// Route::group(['middleware' => 'api'], function ($router) {   
//     Route::get('usuarios', [UserController::class, 'index']);   
// });
Route::middleware('auth:api')->prefix('system')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    //Rutas para el controlador Software
    Route::resource('/software', SoftwareController::class);
    // Ruta para obtener los serv       
    Route::resource('/services', ServicesController::class);

});