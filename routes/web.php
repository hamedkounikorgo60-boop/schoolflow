<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EleveController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\MatiereController;
use App\Http\Controllers\Gestionnaire\NoteController;
use App\Http\Controllers\Enseignant\DashboardController as EnseignantDashboard;
use App\Http\Controllers\Enseignant\NoteController as EnseignantNoteController;

Route::get('/', fn() => redirect()->route('login'));

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:gestionnaire'])
    ->prefix('gestionnaire')
    ->name('gestionnaire.')
    ->group(function () {

        Route::get('/dashboard', fn() => view('gestionnaire.dashboard'))
            ->name('dashboard');

        Route::resource('eleves', EleveController::class);
        Route::resource('classes', ClasseController::class);
        Route::resource('paiements', PaiementController::class);

        Route::get('/paiements/{paiement}/recu',
            [PaiementController::class, 'recu'])
            ->name('paiements.recu');

        Route::get('/paiements/{paiement}/telecharger',
            [PaiementController::class, 'telechargerRecu'])
            ->name('paiements.telecharger');

        Route::get('/impaye',
            [PaiementController::class, 'impaye'])
            ->name('paiements.impaye');

        Route::resource('notes', NoteController::class);

        Route::get('/classement',
            [NoteController::class, 'classement'])
            ->name('notes.classement');

        Route::get('/bulletin',
            [NoteController::class, 'bulletin'])
            ->name('notes.bulletin');
    });

Route::middleware(['auth', 'role:enseignant'])
    ->prefix('enseignant')
    ->name('enseignant.')
    ->group(function () {

        Route::get('/dashboard',
            [EnseignantDashboard::class, 'index'])
            ->name('dashboard');

        Route::get('/notes',
            [EnseignantNoteController::class, 'index'])
            ->name('notes.index');

        Route::get('/notes/create',
            [EnseignantNoteController::class, 'create'])
            ->name('notes.create');

        Route::post('/notes',
            [EnseignantNoteController::class, 'store'])
            ->name('notes.store');

        Route::resource('matieres', MatiereController::class);
    });

use App\Http\Controllers\BulletinController;

Route::middleware(['auth'])
    ->get('/bulletin/{eleve}/{trimestre}',
        [BulletinController::class, 'show'])
    ->name('bulletin.show');

