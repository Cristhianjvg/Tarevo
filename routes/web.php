<?php

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
Route::get('/personal/create', [PersonalController::class, 'create'])
    ->name('personal.create');

Route::get('/tarea', [TareaController::class, 'index'])->middleware('auth')->name('tareas.index');
Route::get('/tarea/create', [TareaController::class, 'create'])
    ->name('tareas.create');
