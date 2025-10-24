<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParticipantsController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ApplicationsController;
use App\Http\Controllers\OffersController;
use App\Http\Controllers\NotaTrabajadorController;

Route::get('/', fn () => redirect()->route('login'));

Route::get('/applications/search', [ApplicationsController::class, 'liveSearch'])
    ->name('applications.search')
    ->middleware(['web', 'auth']);

require __DIR__ . '/auth.php';

Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('register', [RegisteredUserController::class, 'store']);

Route::middleware('auth')->group(function () {
    // Home y métricas
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/metrics', [HomeController::class, 'metrics'])->name('metrics');

    // Participantes
    Route::get('/participants', [ParticipantsController::class,'participants'])->name('participants');
    Route::get('/addparticipant', [ParticipantsController::class,'addParticipant'])->name('addparticipant');
    Route::post('/saveparticipant', [ParticipantsController::class,'saveParticipant'])->name('saveparticipant');
    Route::get('/viewparticipant/{participant}', [ParticipantsController::class,'viewParticipant'])->name('viewparticipant');
    Route::get('/editparticipant/{participant}', [ParticipantsController::class,'editParticipant'])->name('editparticipant');
    Route::post('/updateparticipant/{participant}', [ParticipantsController::class,'updateParticipant'])->name('updateparticipant');
    Route::post('/deleteparticipant/{participant}', [ParticipantsController::class,'deleteParticipant'])->name('deleteparticipant');

    // Empresas
    Route::get('/companies', [CompaniesController::class, 'index'])->name('companies');
    Route::get('/companies/new', [CompaniesController::class, 'create'])->name('addcompany');
    Route::post('/companies', [CompaniesController::class, 'store'])->name('savecompany');
    Route::get('/companies/{company}', [CompaniesController::class, 'show'])->name('viewcompany');
    Route::get('/companies/{company}/edit', [CompaniesController::class, 'edit'])->name('editcompany');
    Route::post('/companies/{company}', [CompaniesController::class, 'update'])->name('updatecompany');
    Route::delete('/companies/{company}', [CompaniesController::class, 'destroy'])->name('deletecompany');

    // Ofertas
    Route::get('/offers', [OffersController::class, 'index'])->name('offers');
    Route::get('/offers/new', [OffersController::class, 'create'])->name('addoffer');
    Route::post('/offers', [OffersController::class, 'store'])->name('saveoffer');
    Route::get('/offers/{offer}', [OffersController::class, 'show'])->name('viewoffer');
    Route::get('/offers/{offer}/edit', [OffersController::class, 'edit'])->name('editoffer');
    Route::post('/offers/{offer}', [OffersController::class, 'update'])->name('updateoffer');
    Route::delete('/offers/{offer}', [OffersController::class, 'destroy'])->name('deleteoffer');

    // Candidaturas
    Route::get('/applications', [ApplicationsController::class, 'index'])->name('applications');
    Route::get('/applications/new', [ApplicationsController::class, 'create'])->name('addapplication');
    Route::post('/applications', [ApplicationsController::class, 'store'])->name('saveapplication');
    Route::get('/applications/{application}', [ApplicationsController::class, 'show'])->name('viewapplication');
    Route::get('/applications/{application}/edit', [ApplicationsController::class, 'edit'])->name('editapplication');
    Route::post('/applications/{application}', [ApplicationsController::class, 'update'])->name('updateapplication');
    Route::delete('/applications/{application}', [ApplicationsController::class, 'destroy'])->name('deleteapplication');

    // 📝 Notas de Trabajador (orden correcto)
    Route::get('/notas', [NotaTrabajadorController::class, 'index'])->name('notas.index');
    Route::get('/notas/create', [NotaTrabajadorController::class, 'create'])->name('notas.create'); // <- antes
    Route::post('/notas', [NotaTrabajadorController::class, 'store'])->name('notas.store');
    Route::get('/notas/{nota}', [NotaTrabajadorController::class, 'show'])->name('notas.show');
    Route::get('/notas/{nota}/edit', [NotaTrabajadorController::class, 'edit'])->name('notas.edit');
    Route::put('/notas/{nota}', [NotaTrabajadorController::class, 'update'])->name('notas.update');
    Route::delete('/notas/{nota}', [NotaTrabajadorController::class, 'destroy'])->name('notas.destroy');

    Route::get('/ss-records',            [\App\Http\Controllers\SSRecordsController::class, 'index'])->name('ss.index');
    Route::get('/ss-records/create',     [\App\Http\Controllers\SSRecordsController::class, 'create'])->name('ss.create');
    Route::post('/ss-records',           [\App\Http\Controllers\SSRecordsController::class, 'store'])->name('ss.store');
    Route::get('/ss-records/{ss}',       [\App\Http\Controllers\SSRecordsController::class, 'show'])->name('ss.show');
    Route::get('/ss-records/{ss}/edit',  [\App\Http\Controllers\SSRecordsController::class, 'edit'])->name('ss.edit');
    Route::put('/ss-records/{ss}',       [\App\Http\Controllers\SSRecordsController::class, 'update'])->name('ss.update');
    Route::delete('/ss-records/{ss}',    [\App\Http\Controllers\SSRecordsController::class, 'destroy'])->name('ss.destroy');
});
