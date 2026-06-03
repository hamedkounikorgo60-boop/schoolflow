<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\Eleve;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PaiementController extends Controller
{
    public function index(Request $request)
    {
        $query = Paiement::with('eleve.classe');
    
        if ($request->filled('type')) {
            $query->where('type_paiement', $request->type);
        }
    
        if ($request->filled('trimestre')) {
            $query->where('trimestre', $request->trimestre);
        }
    
        if ($request->filled('recherche')) {
            $search = $request->recherche;
    
            $query->whereHas('eleve', function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenoms', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%");
            });
        }
    
        $paiements = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();
    
        return view('paiements.index', compact('paiements'));
    }
    public function create()
    {
        $eleves = Eleve::with('classe')
            ->orderBy('nom')
            ->get();

        return view('paiements.create', compact('eleves'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'eleve_id'      => 'required|exists:eleves,id',
            'montant'       => 'required|numeric|min:1',
            'type_paiement' => 'required|in:scolarite,inscription,cantine,transport,fournitures,autre',
            'trimestre'     => 'required|in:1,2,3',
            'date_paiement' => 'required|date',
        ]);

        $paiement = Paiement::create([
            'eleve_id'      => $request->eleve_id,
            'montant'       => $request->montant,
            'type_paiement' => $request->type_paiement,
            'trimestre'     => $request->trimestre,
            'mois'          => $request->mois,
            'date_paiement' => $request->date_paiement,
            'statut'        => 'paye',
            'recu_numero'   => 'RECU-' . strtoupper(uniqid()),
        ]);

        return redirect()
            ->route('gestionnaire.paiements.recu', $paiement)
            ->with('success', 'Paiement enregistré avec succès.');
    }

    public function recu(Paiement $paiement)
{
    $eleve = $paiement->eleve()->with('classe')->first();

    $paiements = Paiement::where('eleve_id', $eleve->id)
        ->orderBy('date_paiement', 'desc')
        ->get();

    $total = $paiements->sum('montant');

    return view('paiements.recu', compact(
        'paiement',
        'eleve',
        'paiements',
        'total'
    ));
}

    public function telechargerRecu(Paiement $paiement)
    {
        $eleve = $paiement->eleve()->with('classe')->first();

        $paiements = Paiement::where('eleve_id', $eleve->id)
            ->orderBy('date_paiement', 'desc')
            ->get();

        $total = $paiements->sum('montant');

        $pdf = Pdf::loadView('paiements.recu_pdf', compact(
            'eleve',
            'paiements',
            'total'
        ));

        return $pdf->download(
            'recu-' . $eleve->nom . '.pdf'
        );
    }

    public function impaye()
    {
        $impayes = collect();

        foreach (Eleve::with('classe')->get() as $eleve) {

            $types = [
                'scolarite',
                'inscription',
                'cantine',
                'transport',
                'fournitures'
            ];

            foreach ($types as $type) {

                $frais_total = $this->fraisParType(
                    $eleve->classe,
                    $type
                );

                $total_paye = Paiement::where('eleve_id', $eleve->id)
                    ->where('type_paiement', $type)
                    ->sum('montant');

                if ($total_paye < $frais_total) {

                    $impayes->push((object)[
                        'eleve' => $eleve,
                        'type' => ucfirst($type),
                        'frais_total' => $frais_total,
                        'total_paye' => $total_paye,
                        'reste' => $frais_total - $total_paye,
                    ]);
                }
            }
        }

        return view('paiements.impaye', compact('impayes'));
    }

    private function fraisParType($classe, $type)
    {
        return match ($type) {
            'scolarite'   => $classe->frais_scolarite ?? 0,
            'inscription' => $classe->frais_inscription ?? 0,
            'cantine'     => $classe->frais_cantine ?? 0,
            'transport'   => $classe->frais_transport ?? 0,
            'fournitures' => $classe->frais_fournitures ?? 0,
            default       => 0,
        };
    }
}
