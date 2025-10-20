<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login'); // siempre muestra login si no hay sesión
});

Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return view('home'); // página privada tras login
    })->name('home');
});

require __DIR__.'/auth.php'; // rutas de Breeze (login, register, logout, etc.)
