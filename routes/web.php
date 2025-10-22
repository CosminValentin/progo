<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParticipantsController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisteredUserController;

// Raíz → login
Route::get('/', fn () => redirect()->route('login'));

// Rutas de Breeze (login, register, logout, etc.)
require __DIR__ . '/auth.php';

// Rutas de registro
Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('register', [RegisteredUserController::class, 'store']);

// Área privada (requiere autenticación)
Route::middleware('auth')->group(function () {
    // Home y métricas
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/metrics', [HomeController::class, 'metrics'])->name('metrics'); // NUEVA

    // Rutas de Participantes
    Route::get('/participants', [ParticipantsController::class, 'participants'])->name('participants');
    Route::get('/addparticipant', [ParticipantsController::class, 'addParticipant'])->name('addparticipant');
    Route::post('/saveparticipant', [ParticipantsController::class, 'saveParticipant'])->name('saveparticipant');
    Route::get('/viewparticipant/{participant}', [ParticipantsController::class, 'viewParticipant'])->name('viewparticipant');
    Route::get('/editparticipant/{participant}', [ParticipantsController::class, 'editParticipant'])->name('editparticipant');
    Route::post('/updateparticipant/{participant}', [ParticipantsController::class, 'updateParticipant'])->name('updateparticipant');
    Route::post('/deleteparticipant/{participant}', [ParticipantsController::class, 'deleteParticipant'])->name('deleteparticipant');

    // Rutas de Empresas
    Route::get('/companies',                    [CompaniesController::class, 'index'])->name('companies');
    Route::get('/companies/new',                [CompaniesController::class, 'create'])->name('addcompany');
    Route::post('/companies',                   [CompaniesController::class, 'store'])->name('savecompany');
    Route::get('/companies/{company}',          [CompaniesController::class, 'show'])->name('viewcompany');
    Route::get('/companies/{company}/edit',     [CompaniesController::class, 'edit'])->name('editcompany');
    Route::post('/companies/{company}',         [CompaniesController::class, 'update'])->name('updatecompany');
    Route::delete('/companies/{company}',       [CompaniesController::class, 'destroy'])->name('deletecompany');
});