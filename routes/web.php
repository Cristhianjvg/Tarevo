<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;



Route::get('/', [LoginController::class, 'show'])->name('login');

Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');
