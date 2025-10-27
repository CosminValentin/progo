<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ParticipantsController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ApplicationsController;
use App\Http\Controllers\OffersController;
use App\Http\Controllers\NotaTrabajadorController;
use App\Http\Controllers\DocumentsController;
use App\Http\Controllers\CvController;



Route::get('/', fn () => redirect()->route('login'));

Route::get('/applications/search', [ApplicationsController::class, 'liveSearch'])
    ->name('applications.search')
    ->middleware(['web', 'auth']);

Route::get('/participants/{participant}/timeline', [ParticipantsController::class, 'timeline'])
    ->name('participants.timeline')
    ->middleware('auth');

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

    Route::get('/documents',        [DocumentsController::class, 'index'])->name('documents.index');
    Route::get('/documents/new',    [DocumentsController::class, 'create'])->name('documents.create');
    Route::post('/documents',       [DocumentsController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}/download', [DocumentsController::class, 'download'])->name('documents.download');
    Route::delete('/documents/{document}',       [DocumentsController::class, 'destroy'])->name('documents.destroy');

    Route::get('/insertion_checks',            [\App\Http\Controllers\InsertionChecksController::class, 'index'])->name('insertion_checks.index');
    Route::get('/insertion_checks/create',     [\App\Http\Controllers\InsertionChecksController::class, 'create'])->name('insertion_checks.create');
    Route::post('/insertion_checks',           [\App\Http\Controllers\InsertionChecksController::class, 'store'])->name('insertion_checks.store');
    Route::get('/insertion_checks/{insertion_check}',        [\App\Http\Controllers\InsertionChecksController::class, 'show'])->name('insertion_checks.show');
    Route::get('/insertion_checks/{insertion_check}/edit',   [\App\Http\Controllers\InsertionChecksController::class, 'edit'])->name('insertion_checks.edit');
    Route::put('/insertion_checks/{insertion_check}',        [\App\Http\Controllers\InsertionChecksController::class, 'update'])->name('insertion_checks.update');
    Route::delete('/insertion_checks/{insertion_check}',     [\App\Http\Controllers\InsertionChecksController::class, 'destroy'])->name('insertion_checks.destroy');    

    Route::get('/agreements',                [\App\Http\Controllers\AgreementsController::class, 'index'])->name('agreements.index');
    Route::get('/agreements/create',         [\App\Http\Controllers\AgreementsController::class, 'create'])->name('agreements.create');
    Route::post('/agreements',               [\App\Http\Controllers\AgreementsController::class, 'store'])->name('agreements.store');
    Route::get('/agreements/{agreement}',    [\App\Http\Controllers\AgreementsController::class, 'show'])->name('agreements.show');
    Route::get('/agreements/{agreement}/edit', [\App\Http\Controllers\AgreementsController::class, 'edit'])->name('agreements.edit');
    Route::put('/agreements/{agreement}',    [\App\Http\Controllers\AgreementsController::class, 'update'])->name('agreements.update');
    Route::delete('/agreements/{agreement}', [\App\Http\Controllers\AgreementsController::class, 'destroy'])->name('agreements.destroy');

    Route::get('/contracts',                   [\App\Http\Controllers\ContractsController::class, 'index'])->name('contracts.index');
    Route::get('/contracts/create',            [\App\Http\Controllers\ContractsController::class, 'create'])->name('contracts.create');
    Route::post('/contracts',                  [\App\Http\Controllers\ContractsController::class, 'store'])->name('contracts.store');
    Route::get('/contracts/{contract}',        [\App\Http\Controllers\ContractsController::class, 'show'])->name('contracts.show');
    Route::get('/contracts/{contract}/edit',   [\App\Http\Controllers\ContractsController::class, 'edit'])->name('contracts.edit');
    Route::put('/contracts/{contract}',        [\App\Http\Controllers\ContractsController::class, 'update'])->name('contracts.update');
    Route::delete('/contracts/{contract}',     [\App\Http\Controllers\ContractsController::class, 'destroy'])->name('contracts.destroy');
    Route::get('/participants/{participant}/timeline', [ParticipantsController::class, 'timeline'])->name('participants.timeline');
    Route::get('/offers/{offer}', [\App\Http\Controllers\OffersController::class, 'show'])->name('offers.show');

    Route::get('/cvs',               [\App\Http\Controllers\CvController::class, 'index'])->name('cvs.index');
    Route::get('/cvs/create',        [\App\Http\Controllers\CvController::class, 'create'])->name('cvs.create');
    Route::post('/cvs',              [\App\Http\Controllers\CvController::class, 'store'])->name('cvs.store');
    Route::get('/cvs/{cv}/edit',     [\App\Http\Controllers\CvController::class, 'edit'])->name('cvs.edit');
    Route::put('/cvs/{cv}',          [\App\Http\Controllers\CvController::class, 'update'])->name('cvs.update');
    Route::delete('/cvs/{cv}',       [\App\Http\Controllers\CvController::class, 'destroy'])->name('cvs.destroy');
    Route::get('/cvs/{cv}/download', [\App\Http\Controllers\CvController::class, 'download'])->name('cvs.download');

});

Route::middleware(['auth'])->group(function () {
    Route::resource('contracts', \App\Http\Controllers\ContractsController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/participants/{participant}/timeline', [\App\Http\Controllers\ParticipantsController::class, 'timeline'])
        ->name('participants.timeline');
});