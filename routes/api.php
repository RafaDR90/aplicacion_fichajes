<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Dispositivo\DispositivoController;


//open routes
Route::post('/login', [ApiController::class, 'login']);
Route::get('/get-fecha', [ApiController::class, 'fechaYHora']);
Route::post('/email-exists', [ApiController::class, 'emailExists']);

//protected routes
Route::group(['middleware' => ['auth:api']], function () {
    //ApiController
    Route::post('/register', [ApiController::class, 'register'])->middleware(AdminMiddleware::class);
    Route::get('/profile', [ApiController::class, 'profile']);
    Route::get('/logout', [ApiController::class, 'logout']);
    Route::post('/reset-pw-token', [ApiController::class, 'getResetPasswordToken']);
    Route::post('/reset-pw', [ApiController::class, 'changuePassword']);
    
    //DispositivoController
    Route::post('/add-dispositivo', [DispositivoController::class, 'store'])->middleware(AdminMiddleware::class);
    Route::post('/get-dispositivos-user-id', [DispositivoController::class, 'showDispositivosUserId'])->middleware(AdminMiddleware::class);
    Route::get('/get-user-dispositivos', [DispositivoController::class, 'showDispositivos']);
    Route::post('/delete-dispositivo', [DispositivoController::class, 'delete'])->middleware(AdminMiddleware::class);
});
