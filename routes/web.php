<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParticipantsController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\HomeController;

// Raíz → login
Route::get('/', fn () => redirect()->route('login'));

// Rutas de Breeze (login, register, logout, etc.)
require __DIR__ . '/auth.php';

// Área privada
Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/metrics', [HomeController::class, 'metrics'])->name('metrics'); // <- NUEVA
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

    //
    Route::get('/companies', [CompaniesController::class, 'companies'])->name('companies');

    Route::get('/addcompany', [CompaniesController::class, 'addCompany'])->name('addcompany');
    Route::post('/savecompany', [CompaniesController::class, 'saveCompany'])->name('savecompany');

    Route::get('/viewcompany/{company}', [CompaniesController::class, 'viewCompany'])->name('viewcompany');

    Route::get('/editcompany/{company}', [CompaniesController::class, 'editCompany'])->name('editcompany');
    Route::post('/updatecompany/{company}', [CompaniesController::class, 'updateCompany'])->name('updatecompany');

    Route::post('/deletecompany/{company}', [CompaniesController::class, 'deleteCompany'])->name('deletecompany');
});

