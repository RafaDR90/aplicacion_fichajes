<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\FichajeController;
use App\Http\Middleware\Admin;
use App\Http\Controllers\Web\RoleController;


/*Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});*/

Route::get('/', [UserController::class, 'index']);

Route::group(['middleware' => Admin::class], function () {
    Route::get('/usuarios', [UserController::class, 'showUsers'])->name('showUsers');
    Route::post('/cambiar-rol', [RoleController::class, 'cambiarRol'])->name('cambiarRol');
    Route::post('/perfil', [UserController::class, 'showUser'])->name('showUser');

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
