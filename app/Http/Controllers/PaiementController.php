<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\Eleve;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
            'observation'   => 'nullable|string|max:500',
            'mois'          => 'nullable|string|max:20',
        ]);

        $eleve = Eleve::with('classe')->findOrFail($request->eleve_id);
        $annee = \Carbon\Carbon::parse($request->date_paiement)->year;

        $fraisClasse = $eleve->classe->fraisTotalAnnuel();
        $dejaPaye = (float) Paiement::where('eleve_id', $eleve->id)
            ->whereYear('date_paiement', $annee)
            ->sum('montant');
        $resteClasse = max(0, $fraisClasse - $dejaPaye);

        if ((float) $request->montant > $resteClasse) {
            return back()
                ->withInput()
                ->withErrors([
                    'montant' => 'Le montant ne peut pas dépasser le reste à payer pour cette classe : '
                        . number_format($resteClasse, 0, ',', ' ')
                        . ' FCFA (total annuel de la classe : '
                        . number_format($fraisClasse, 0, ',', ' ')
                        . ' FCFA, déjà payé : '
                        . number_format($dejaPaye, 0, ',', ' ')
                        . ' FCFA).',
                ]);
        }

        $paiement = Paiement::create([
            'eleve_id'      => $request->eleve_id,
            'montant'       => $request->montant,
            'type_paiement' => $request->type_paiement,
            'trimestre'     => $request->trimestre,
            'mois'          => $request->mois,
            'date_paiement' => $request->date_paiement,
            'statut'        => 'paye',
            'recu_numero'   => 'REC-' . now()->format('Ymd') . '-' . strtoupper(Str::random(12)),
            'observation'   => $request->observation,
        ]);

        return redirect()
            ->route('gestionnaire.paiements.recu', $paiement)
            ->with('success', 'Paiement enregistré avec succès.');
    }

    public function recu(Paiement $paiement)
    {
        return view('paiements.recu', $this->donneesRecu($paiement));
    }

    public function telechargerRecu(Paiement $paiement)
    {
        $donnees = $this->donneesRecu($paiement);

        $pdf = Pdf::loadView('paiements.recu_pdf', $donnees)
            ->setPaper('a4', 'portrait');

        return $pdf->download('recu-' . $paiement->recu_numero . '.pdf');
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
                'fournitures',
                'autre',
            ];

            foreach ($types as $type) {
                $frais_total = $eleve->classe->fraisParType($type);

                $total_paye = Paiement::where('eleve_id', $eleve->id)
                    ->where('type_paiement', $type)
                    ->sum('montant');

                if ($total_paye < $frais_total) {
                    $impayes->push((object) [
                        'eleve'       => $eleve,
                        'type'        => ucfirst($type),
                        'frais_total' => $frais_total,
                        'total_paye'  => $total_paye,
                        'reste'       => $frais_total - $total_paye,
                    ]);
                }
            }
        }

        return view('paiements.impaye', compact('impayes'));
    }

    private function donneesRecu(Paiement $paiement): array
    {
        $paiement->load('eleve.classe');
        $eleve  = $paiement->eleve;
        $classe = $eleve->classe;
        $annee  = \Carbon\Carbon::parse($paiement->date_paiement)->year;

        $fraisTotalClasse = $classe->fraisTotalAnnuel();

        $totalPayeTousTypes = (float) Paiement::where('eleve_id', $eleve->id)
            ->whereYear('date_paiement', $annee)
            ->sum('montant');

        $resteClasse = max(0, $fraisTotalClasse - $totalPayeTousTypes);

        return compact(
            'paiement',
            'eleve',
            'fraisTotalClasse',
            'totalPayeTousTypes',
            'resteClasse'
        );
    }
}
