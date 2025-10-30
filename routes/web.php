<?php

use Illuminate\Support\Facades\Route;

// Auth + Home
use App\Http\Controllers\HomeController;


// Participants & Notas
use App\Http\Controllers\ParticipantsController;
use App\Http\Controllers\NotaTrabajadorController;

// Companies / Offers / Applications
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\OffersController;
use App\Http\Controllers\ApplicationsController;

// Documents / CVs / Agreements / Contracts / SS / Insertion
use App\Http\Controllers\DocumentsController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\AgreementsController;
use App\Http\Controllers\ContractsController;
use App\Http\Controllers\SSRecordsController;
use App\Http\Controllers\InsertionChecksController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('/', fn () => redirect()->route('login'));
require __DIR__ . '/auth.php';



Route::get('/register', [RegisteredUserController::class, 'show'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');

Route::get('/home', function () {
    return view('home'); 
})->name('home');


Route::middleware('auth')->group(function () {

    // === Home & métricas ===
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/metrics', [HomeController::class, 'metrics'])->name('metrics');

    // === Participants ===
    Route::get('/participants',                    [ParticipantsController::class, 'participants'])->name('participants');
    Route::get('/addparticipant',                  [ParticipantsController::class, 'addParticipant'])->name('addparticipant');
    Route::post('/saveparticipant',                [ParticipantsController::class, 'saveParticipant'])->name('saveparticipant');
    Route::get('/viewparticipant/{participant}',   [ParticipantsController::class, 'viewParticipant'])->name('viewparticipant');
    Route::get('/editparticipant/{participant}',   [ParticipantsController::class, 'editParticipant'])->name('editparticipant');
    Route::post('/updateparticipant/{participant}',[ParticipantsController::class, 'updateParticipant'])->name('updateparticipant');
    Route::post('/deleteparticipant/{participant}',[ParticipantsController::class, 'deleteParticipant'])->name('deleteparticipant');
    Route::get('/participants/{participant}/timeline', [ParticipantsController::class, 'timeline'])->name('participants.timeline');

    // === Notas de trabajador ===
    Route::get('/notas',                [NotaTrabajadorController::class, 'index'])->name('notas.index');
    Route::get('/notas/create',         [NotaTrabajadorController::class, 'create'])->name('notas.create');
    Route::post('/notas',               [NotaTrabajadorController::class, 'store'])->name('notas.store');
    Route::get('/notas/{nota}',         [NotaTrabajadorController::class, 'show'])->name('notas.show');
    Route::get('/notas/{nota}/edit',    [NotaTrabajadorController::class, 'edit'])->name('notas.edit');
    Route::put('/notas/{nota}',         [NotaTrabajadorController::class, 'update'])->name('notas.update');
    Route::delete('/notas/{nota}',      [NotaTrabajadorController::class, 'destroy'])->name('notas.destroy');

    // === Companies ===
    Route::get('/companies',                 [CompaniesController::class, 'index'])->name('companies');
    Route::get('/companies/new',             [CompaniesController::class, 'create'])->name('addcompany');
    Route::post('/companies',                [CompaniesController::class, 'store'])->name('savecompany');
    Route::get('/companies/{company}',       [CompaniesController::class, 'show'])->name('viewcompany');
    Route::get('/companies/{company}/edit',  [CompaniesController::class, 'edit'])->name('editcompany');
    Route::post('/companies/{company}',      [CompaniesController::class, 'update'])->name('updatecompany');
    Route::delete('/companies/{company}',    [CompaniesController::class, 'destroy'])->name('deletecompany');

    // === Offers ===
    Route::get('/offers',                [OffersController::class, 'index'])->name('offers');
    Route::get('/offers/new',            [OffersController::class, 'create'])->name('addoffer');
    Route::post('/offers',               [OffersController::class, 'store'])->name('saveoffer');
    Route::get('/offers/{offer}',        [OffersController::class, 'show'])->name('viewoffer');
    Route::get('/offers/{offer}/edit',   [OffersController::class, 'edit'])->name('editoffer');
    Route::post('/offers/{offer}',       [OffersController::class, 'update'])->name('updateoffer');
    Route::delete('/offers/{offer}',     [OffersController::class, 'destroy'])->name('deleteoffer');

    // (alias opcional que ya usabas)
    Route::get('/offers/{offer}', [OffersController::class, 'show'])->name('offers.show');

    // === Applications ===
    Route::get('/applications',                   [ApplicationsController::class, 'index'])->name('applications');
    Route::get('/applications/new',               [ApplicationsController::class, 'create'])->name('addapplication');
    Route::post('/applications',                  [ApplicationsController::class, 'store'])->name('saveapplication');
    Route::get('/applications/{application}',     [ApplicationsController::class, 'show'])->name('viewapplication');
    Route::get('/applications/{application}/edit',[ApplicationsController::class, 'edit'])->name('editapplication');
    Route::post('/applications/{application}',    [ApplicationsController::class, 'update'])->name('updateapplication');
    Route::delete('/applications/{application}',  [ApplicationsController::class, 'destroy'])->name('deleteapplication');

    // Buscador en vivo
    Route::get('/applications/search', [ApplicationsController::class, 'liveSearch'])->name('applications.search');

    // === Documents ===
    Route::get('/documents',              [DocumentsController::class, 'index'])->name('documents.index');
    Route::get('/documents/new',          [DocumentsController::class, 'create'])->name('documents.create');
    Route::post('/documents',             [DocumentsController::class, 'store'])->name('documents.store');
    // Importante: descarga ANTES de show para no colisionar con {document}
    Route::get('/documents/{document}/download', [DocumentsController::class, 'download'])->name('documents.download');
    Route::get('/documents/{document}',          [DocumentsController::class, 'show'])->name('documents.show');
    Route::delete('/documents/{document}',       [DocumentsController::class, 'destroy'])->name('documents.destroy');

    // === Agreements (convenios) ===
    Route::get('/agreements',                 [AgreementsController::class, 'index'])->name('agreements.index');
    Route::get('/agreements/create',          [AgreementsController::class, 'create'])->name('agreements.create');
    Route::post('/agreements',                [AgreementsController::class, 'store'])->name('agreements.store');
    Route::get('/agreements/{agreement}',     [AgreementsController::class, 'show'])->name('agreements.show');
    Route::get('/agreements/{agreement}/edit',[AgreementsController::class, 'edit'])->name('agreements.edit');
    Route::put('/agreements/{agreement}',     [AgreementsController::class, 'update'])->name('agreements.update');
    Route::delete('/agreements/{agreement}',  [AgreementsController::class, 'destroy'])->name('agreements.destroy');

    // === Contracts ===
    Route::get('/contracts',                 [ContractsController::class, 'index'])->name('contracts.index');
    Route::get('/contracts/create',          [ContractsController::class, 'create'])->name('contracts.create');
    Route::post('/contracts',                [ContractsController::class, 'store'])->name('contracts.store');
    Route::get('/contracts/{contract}',      [ContractsController::class, 'show'])->name('contracts.show');
    Route::get('/contracts/{contract}/edit', [ContractsController::class, 'edit'])->name('contracts.edit');
    Route::put('/contracts/{contract}',      [ContractsController::class, 'update'])->name('contracts.update');
    Route::delete('/contracts/{contract}',   [ContractsController::class, 'destroy'])->name('contracts.destroy');

    // === Seguridad Social ===
    Route::get('/ss-records',            [SSRecordsController::class, 'index'])->name('ss.index');
    Route::get('/ss-records/create',     [SSRecordsController::class, 'create'])->name('ss.create');
    Route::post('/ss-records',           [SSRecordsController::class, 'store'])->name('ss.store');
    Route::get('/ss-records/{ss}',       [SSRecordsController::class, 'show'])->name('ss.show');
    Route::get('/ss-records/{ss}/edit',  [SSRecordsController::class, 'edit'])->name('ss.edit');
    Route::put('/ss-records/{ss}',       [SSRecordsController::class, 'update'])->name('ss.update');
    Route::delete('/ss-records/{ss}',    [SSRecordsController::class, 'destroy'])->name('ss.destroy');

    // === Insertion Checks ===
    Route::get('/insertion_checks',              [InsertionChecksController::class, 'index'])->name('insertion_checks.index');
    Route::get('/insertion_checks/create',       [InsertionChecksController::class, 'create'])->name('insertion_checks.create');
    Route::post('/insertion_checks',             [InsertionChecksController::class, 'store'])->name('insertion_checks.store');
    Route::get('/insertion_checks/{insertion_check}',      [InsertionChecksController::class, 'show'])->name('insertion_checks.show');
    Route::get('/insertion_checks/{insertion_check}/edit', [InsertionChecksController::class, 'edit'])->name('insertion_checks.edit');
    Route::put('/insertion_checks/{insertion_check}',      [InsertionChecksController::class, 'update'])->name('insertion_checks.update');
    Route::delete('/insertion_checks/{insertion_check}',   [InsertionChecksController::class, 'destroy'])->name('insertion_checks.destroy');

    Route::get('/cvs',               [\App\Http\Controllers\CvController::class, 'index'])->name('cvs.index');
    Route::get('/cvs/create',        [\App\Http\Controllers\CvController::class, 'create'])->name('cvs.create');
    Route::post('/cvs',              [\App\Http\Controllers\CvController::class, 'store'])->name('cvs.store');
    Route::get('/cvs/{cv}',          [\App\Http\Controllers\CvController::class, 'show'])->name('cvs.show'); // <-- VER inline
    Route::get('/cvs/{cv}/edit',     [\App\Http\Controllers\CvController::class, 'edit'])->name('cvs.edit');
    Route::put('/cvs/{cv}',          [\App\Http\Controllers\CvController::class, 'update'])->name('cvs.update');
    Route::delete('/cvs/{cv}',       [\App\Http\Controllers\CvController::class, 'destroy'])->name('cvs.destroy');
    Route::get('/cvs/{cv}/download', [\App\Http\Controllers\CvController::class, 'download'])->name('cvs.download');


    Route::get('/offers', [OffersController::class, 'index'])->name('offers');

    // CRUD básico con nombres usados en la vista modal
    Route::post('/offers', [OffersController::class, 'store'])->name('offers.store');
    Route::get('/offers/{offer}', [OffersController::class, 'show'])->name('offers.show');
    Route::match(['put','patch'], '/offers/{offer}', [OffersController::class, 'update'])->name('offers.update');
    Route::delete('/offers/{offer}', [OffersController::class, 'destroy'])->name('offers.destroy');

    // (Opcionales, si usas páginas de crear/editar fuera de la ficha)
    Route::get('/offers/create', [OffersController::class, 'create'])->name('offers.create');
    Route::get('/offers/{offer}/edit', [OffersController::class, 'edit'])->name('offers.edit');
});

// (Resource vacío opcional; puedes eliminarlo si no lo usas)
Route::middleware('auth')->group(function () {
    Route::resource('contracts', ContractsController::class)->only([]);
});

Route::middleware('auth')->group(function () {
    Route::resource('offers', OffersController::class);
});

Route::resource('applications', \App\Http\Controllers\ApplicationsController::class)->only([
  'index','create','store','show','edit','update','destroy'
]);

Route::resource('applications', ApplicationsController::class)
    ->names('applications');

Route::resource('ss', \App\Http\Controllers\SocialSecurityController::class)
  ->only(['store','update','destroy']);