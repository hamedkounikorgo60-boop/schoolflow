<?php

namespace App\Http\Controllers;

use App\Models\Eleve;
use App\Models\Enseignant;
use App\Models\Paiement;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEleves = Eleve::count();
        $totalEnseignants = Enseignant::count();
        $totalPaiements = Paiement::sum('montant');

        $impayes = Paiement::where('statut', 'impaye')->count();

        return view('dashboard', compact(
            'totalEleves',
            'totalEnseignants',
            'totalPaiements',
            'impayes'
        ));
    }
}
