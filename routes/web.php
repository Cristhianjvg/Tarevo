<?php

use App\Http\Controllers\InstalacionController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\TareaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'show'])->name('login');

Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::get('/personal', [PersonalController::class, 'index'])->middleware('auth')->name('personal.index');
Route::post('/personal', [PersonalController::class, 'store'])->middleware('auth')->name('personal.store');
Route::put('/personal/{user}', [PersonalController::class, 'update'])->middleware('auth')->name('personal.update');

Route::get('/instalaciones', [InstalacionController::class, 'index'])->middleware('auth')->name('instalaciones.index');
Route::post('/instalaciones', [InstalacionController::class, 'store'])->middleware('auth')->name('instalaciones.store');

Route::get('/tarea', [TareaController::class, 'index'])->middleware('auth')->name('tareas.index');
Route::get('/tarea/create', [TareaController::class, 'create'])
    ->name('tareas.create');
