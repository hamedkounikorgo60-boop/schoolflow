<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EleveController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\MatiereController;
use App\Http\Controllers\Gestionnaire\NoteController;
use App\Http\Controllers\Gestionnaire\EnseignantController as GestionnaireEnseignantController;
use App\Http\Controllers\Gestionnaire\ParentController as GestionnaireParentController;
use App\Http\Controllers\Gestionnaire\MatiereController as GestionnaireMatiereController;
use App\Http\Controllers\Enseignant\DashboardController as EnseignantDashboard;
use App\Http\Controllers\Enseignant\NoteController as EnseignantNoteController;

/**
 * =============================================
 * ROUTES D'AUTHENTIFICATION
 * =============================================
 */

// Page d'accueil - redirige vers la page de connexion
Route::get('/', fn() => redirect()->route('login'));

// Affichage du formulaire de connexion
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Traitement de la connexion (POST)
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// Déconnexion
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/**
 * =============================================
 * ROUTES DU GESTIONNAIRE (Administrateur)
 * =============================================
 * Toutes les routes commencent par /gestionnaire
 * Protégées par l'authentification et le rôle 'gestionnaire'
 */
Route::middleware(['auth', 'role:gestionnaire'])
    ->prefix('gestionnaire')
    ->name('gestionnaire.')
    ->group(function () {

        // Tableau de bord du gestionnaire
        Route::get('/dashboard', fn() => view('gestionnaire.dashboard'))
            ->name('dashboard');

        /**
         * ROUTES POUR LES ÉLÈVES
         * Permet de voir, créer, modifier et supprimer les élèves
         */
        Route::get('/eleves', [EleveController::class, 'index'])->name('eleves.index');           // Liste des élèves
        Route::get('/eleves/create', [EleveController::class, 'create'])->name('eleves.create'); // Formulaire de création
        Route::post('/eleves', [EleveController::class, 'store'])->name('eleves.store');         // Enregistrement
        Route::get('/eleves/{eleve}', [EleveController::class, 'show'])->name('eleves.show');    // Détails d'un élève
        Route::get('/eleves/{eleve}/edit', [EleveController::class, 'edit'])->name('eleves.edit'); // Formulaire de modification
        Route::put('/eleves/{eleve}', [EleveController::class, 'update'])->name('eleves.update'); // Mise à jour
        Route::delete('/eleves/{eleve}', [EleveController::class, 'destroy'])->name('eleves.destroy'); // Suppression

        /**
         * ROUTES POUR LES CLASSES
         */
        Route::resource('classes', ClasseController::class);

        /**
         * ROUTES POUR LES ENSEIGNANTS
         */
        Route::get('/enseignants', [GestionnaireEnseignantController::class, 'index'])
            ->name('enseignants.index');
        Route::get('/enseignants/create', [GestionnaireEnseignantController::class, 'create'])
            ->name('enseignants.create');
        Route::post('/enseignants', [GestionnaireEnseignantController::class, 'store'])
            ->name('enseignants.store');
        Route::get('/enseignants/{user}/edit', [GestionnaireEnseignantController::class, 'edit'])
            ->name('enseignants.edit');
        Route::put('/enseignants/{user}', [GestionnaireEnseignantController::class, 'update'])
            ->name('enseignants.update');

        /**
         * ROUTES POUR LES PARENTS
         */
        Route::get('/parents', [GestionnaireParentController::class, 'index'])
            ->name('parents.index');
        Route::get('/parents/create', [GestionnaireParentController::class, 'create'])
            ->name('parents.create');
        Route::post('/parents', [GestionnaireParentController::class, 'store'])
            ->name('parents.store');
        Route::get('/parents/{user}', [GestionnaireParentController::class, 'show'])
            ->name('parents.show');
        Route::get('/parents/{user}/edit', [GestionnaireParentController::class, 'edit'])
            ->name('parents.edit');
        Route::put('/parents/{user}', [GestionnaireParentController::class, 'update'])
            ->name('parents.update');
        Route::delete('/parents/{user}', [GestionnaireParentController::class, 'destroy'])
            ->name('parents.destroy');

        /**
         * ROUTES POUR LES MATIÈRES
         */
        Route::resource('matieres', GestionnaireMatiereController::class)
            ->except(['show']);

        /**
         * ROUTES POUR LES PAIEMENTS
         */
        Route::resource('paiements', PaiementController::class);

        // Affichage du reçu de paiement
        Route::get('/paiements/{paiement}/recu',
            [PaiementController::class, 'recu'])
            ->name('paiements.recu');

        // Téléchargement du reçu en PDF
        Route::get('/paiements/{paiement}/telecharger',
            [PaiementController::class, 'telechargerRecu'])
            ->name('paiements.telecharger');

        // Liste des paiements en attente
        Route::get('/impaye',
            [PaiementController::class, 'impaye'])
            ->name('paiements.impaye');

        /**
         * ROUTES POUR LES NOTES
         */
        Route::resource('notes', NoteController::class);

        // Affichage du classement des élèves par moyenne
        Route::get('/classement',
            [NoteController::class, 'classement'])
            ->name('notes.classement');

        // Génération du bulletin PDF
        Route::get('/bulletin',
            [NoteController::class, 'bulletin'])
            ->name('notes.bulletin');
    });

/**
 * =============================================
 * ROUTES DE L'ENSEIGNANT
 * =============================================
 * Toutes les routes commencent par /enseignant
 * Protégées par l'authentification et le rôle 'enseignant'
 */
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

        Route::post('/notes/bulk',
            [EnseignantNoteController::class, 'storeBulk'])
            ->name('notes.storeBulk');

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

