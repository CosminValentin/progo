<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParticipantsController;

// Raíz → login
Route::get('/', fn () => redirect()->route('login'));

// Rutas de Breeze (login, register, logout, etc.)
require __DIR__ . '/auth.php';

// Área privada
Route::middleware('auth')->group(function () {
    Route::get('/home', fn () => view('home'))->name('home');

    // LISTADO
    Route::get('/participants', [ParticipantsController::class,'participants'])->name('participants');

    // CREAR
    Route::get('/addparticipant', [ParticipantsController::class,'addParticipant'])->name('addparticipant');
    Route::post('/saveparticipant', [ParticipantsController::class,'saveParticipant'])->name('saveparticipant');

    // VER FICHA
    Route::get('/viewparticipant/{participant}', [ParticipantsController::class,'viewParticipant'])->name('viewparticipant');

    // EDITAR
    Route::get('/editparticipant/{participant}', [ParticipantsController::class,'editParticipant'])->name('editparticipant');
    Route::post('/updateparticipant/{participant}', [ParticipantsController::class,'updateParticipant'])->name('updateparticipant');

    // BORRAR
    Route::post('/deleteparticipant/{participant}', [ParticipantsController::class,'deleteParticipant'])->name('deleteparticipant');
});

