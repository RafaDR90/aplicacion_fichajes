<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Middleware\Admin;
use App\Http\Middleware\SuperAdmin;
use App\Http\Controllers\Dispositivo\DispositivoController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\Ubicacion\UbicacionController;


//open routes
Route::post('/login', [ApiController::class, 'login']);
Route::get('/get-fecha', [ApiController::class, 'fechaYHora']);
Route::post('/email-exists', [ApiController::class, 'emailExists']);
//Route::get('/get-users-and-roles', [ApiController::class, 'getUsersAndRoles']);   PUEDO CREARLO SI QUIERO HACER UNA VISTA DE EMPLEADOS Y SUS ROLES

Route::post('/register', [ApiController::class, 'register']);

//protected routes
Route::group(['middleware' => ['auth:api']], function () {
    //ApiController
  //  Route::post('/register', [ApiController::class, 'register'])->middleware(Admin::class);
    Route::get('/profile', [ApiController::class, 'profile']);
    Route::get('/logout', [ApiController::class, 'logout']);
    Route::post('/reset-pw-token', [ApiController::class, 'getResetPasswordToken']);
    Route::post('/reset-pw', [ApiController::class, 'changuePassword']);

    //RoleController
    Route::get('/get-roles', [RoleController::class, 'index'])->middleware(Admin::class);
    Route::post('/add-role', [RoleController::class, 'store'])->middleware(SuperAdmin::class);
    Route::post('/asigna-rol-to-user', [RoleController::class, 'asignaRolToUser'])->middleware(SuperAdmin::class);
    Route::post('/delete-role', [RoleController::class, 'destroy'])->middleware(SuperAdmin::class);
    Route::post('/get-roles-user-id', [RoleController::class, 'showRolesUserId'])->middleware(Admin::class);
    Route::post('/delete-role-user', [RoleController::class, 'deleteRoleFromUser'])->middleware(SuperAdmin::class);
    
    //DispositivoController
    Route::post('/add-dispositivo', [DispositivoController::class, 'store'])->middleware(Admin::class);
    Route::post('/get-dispositivos-user-id', [DispositivoController::class, 'showDispositivosUserId'])->middleware(Admin::class);
    Route::get('/get-user-dispositivos', [DispositivoController::class, 'showDispositivos']);
    Route::post('/delete-dispositivo', [DispositivoController::class, 'delete'])->middleware(Admin::class);

    
    //UbicacionController
    Route::post('/add-ubicacion', [UbicacionController::class, 'store'])->middleware(Admin::class);
    Route::get('/get-ubicaciones', [UbicacionController::class, 'index'])->middleware(Admin::class);

});
