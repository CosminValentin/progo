<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParticipantsController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ApplicationsController;
use App\Http\Controllers\OffersController;


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

    // Rutas de Ofertas
    Route::get('/offers',                 [OffersController::class, 'index'])->name('offers');
    Route::get('/offers/new',             [OffersController::class, 'create'])->name('addoffer');
    Route::post('/offers',                [OffersController::class, 'store'])->name('saveoffer');
    Route::get('/offers/{offer}',         [OffersController::class, 'show'])->name('viewoffer');
    Route::get('/offers/{offer}/edit',    [OffersController::class, 'edit'])->name('editoffer');
    Route::post('/offers/{offer}',        [OffersController::class, 'update'])->name('updateoffer');
    Route::delete('/offers/{offer}',      [OffersController::class, 'destroy'])->name('deleteoffer');

    // Rutas de Applications
    Route::get('/applications',                      [ApplicationsController::class, 'index'])->name('applications');
    Route::get('/applications/new',                  [ApplicationsController::class, 'create'])->name('addapplication');
    Route::post('/applications',                     [ApplicationsController::class, 'store'])->name('saveapplication');
    Route::get('/applications/{application}',        [ApplicationsController::class, 'show'])->name('viewapplication');
    Route::get('/applications/{application}/edit',   [ApplicationsController::class, 'edit'])->name('editapplication');
    Route::post('/applications/{application}',       [ApplicationsController::class, 'update'])->name('updateapplication');
    Route::delete('/applications/{application}',     [ApplicationsController::class, 'destroy'])->name('deleteapplication');
});
