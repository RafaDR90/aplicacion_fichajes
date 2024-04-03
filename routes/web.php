<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\FichajeController;
use App\Http\Middleware\Admin;
use App\Http\Controllers\Web\RoleController;
use App\Http\Controllers\Web\HorarioController;
use App\Http\Controllers\Web\AlertaController;
use App\Http\Controllers\Web\UbicacionController;

/*Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});*/

Route::get('/', [UserController::class, 'index']);

//FICHAJE
Route::get('/fichar', [FichajeController::class, 'fichar'])->name('fichar');

Route::group(['middleware' => Admin::class], function () {
    Route::get('/usuarios', [UserController::class, 'showUsers'])->name('showUsers');
    Route::post('/cambiar-rol', [RoleController::class, 'cambiarRol'])->name('cambiarRol');
    Route::post('/perfil', [UserController::class, 'showUser'])->name('showUser');

    //HORARIO   
    Route::get('/horarios', [HorarioController::class, 'gestionHorarios'])->name('horarios');
    Route::get('/nuevo-horario', [HorarioController::class, 'newHorario'])->name('nuevoHorario');
    Route::post('/nuevo-horario', [HorarioController::class, 'creaHorario'])->name('creaHorario');
    Route::get('/borrar-horario/{id}', [HorarioController::class, 'borrarHorario'])->name('borrarHorario');
    Route::get('/editar-horario/{id}', [HorarioController::class, 'vistaEditaHorario'])->name('vistaEditaHorario');
    Route::post('/editar-horario/{id}', [HorarioController::class, 'editaHorario'])->name('editaHorario');

    //ALERTAS
    Route::get('/alertas', [AlertaController::class, 'index'])->name('alertas');

    //UBICACION
    Route::post('/addUbicacion', [UbicacionController::class, 'addUbicacion'])->name('addUbicacion');
    Route::post('/denyUbicacion', [UbicacionController::class, 'denyUbicacion'])->name('denyUbicacion');
    Route::post('/toggleRequiereUbicacion', [UbicacionController::class, 'toggleRequiereUbicacion'])->name('toggleRequiereUbicacion');

});



Route::get('/dashboard', function () {
    return redirect()->route('fichaje');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/fichaje', [FichajeController::class, 'index'])->name('fichaje');
    

});

require __DIR__.'/auth.php';
