<?php
namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\Eleve;
use App\Models\Classe;
use App\Models\Paiement;

class DashboardController extends Controller
{
    public function index()
    {
        $trimestre = 'trimestre2';

        $nbEleves  = Eleve::where('statut', 'actif')->count();
        $nbClasses = Classe::count();

        $paiementsMois = Paiement::whereMonth('date_paiement', now()->month)
                                  ->whereYear('date_paiement', now()->year)
                                  ->sum('montant');

        $nbImpaye = Eleve::where('statut', 'actif')
                         ->whereDoesntHave('paiements', fn($q) =>
                             $q->where('trimestre', $trimestre)
                               ->where('type_paiement', 'scolarite')
                         )->count();

        $derniersPaiements = Paiement::with('eleve.classe')
                                      ->latest('date_paiement')
                                      ->take(5)
                                      ->get();

        return view('gestionnaire.dashboard', compact(
            'nbEleves', 'nbClasses', 'paiementsMois', 'nbImpaye', 'derniersPaiements'
        ));
    }
}
