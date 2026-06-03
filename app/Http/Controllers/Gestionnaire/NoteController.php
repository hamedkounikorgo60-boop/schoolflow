<?php
namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Eleve;
use App\Models\Classe;
use App\Models\Matiere;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $classes   = Classe::orderBy('nom')->get();
        $classe_id = $request->get('classe_id');
        $trimestre = $request->get('trimestre', 1);
        $eleves    = collect();

        if ($classe_id) {
            $elevesClasse = Eleve::where('classe_id', $classe_id)
                                 ->where('statut', 'actif')
                                 ->get();

            $eleves = $elevesClasse->map(function ($eleve) use ($trimestre) {
                $notes = Note::with('matiere')
                    ->where('eleve_id', $eleve->id)
                    ->where('trimestre', 'trimestre' . $trimestre)
                    ->get();

                $totalCoefs      = $notes->sum(fn($n) => $n->matiere->coefficient);
                $totalPoints     = $notes->sum(fn($n) => $n->note * $n->matiere->coefficient);
                $eleve->moyenne  = $totalCoefs > 0 ? round($totalPoints / $totalCoefs, 2) : null;

                return $eleve;
            })->sortByDesc('moyenne')->values();
        }

        return view('notes.index', compact('classes', 'classe_id', 'trimestre', 'eleves'));
    }

    public function create()
    {
        $eleves   = Eleve::where('statut', 'actif')->orderBy('nom')->get();
        $matieres = Matiere::orderBy('nom')->get();
        return view('notes.create', compact('eleves', 'matieres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'eleve_id'   => 'required|exists:eleves,id',
            'matiere_id' => 'required|exists:matieres,id',
            'note'       => 'required|numeric|min:0|max:20',
            'trimestre'  => 'required|in:trimestre1,trimestre2,trimestre3',
        ]);

        Note::updateOrCreate(
            [
                'eleve_id'   => $request->eleve_id,
                'matiere_id' => $request->matiere_id,
                'trimestre'  => $request->trimestre,
            ],
            [
                'note' => $request->note,
            ]
        );

        return redirect()->route('gestionnaire.notes.index')
                         ->with('success', 'Note enregistrée avec succès.');
    }

    public function classement(Request $request)
    {
        $classes   = Classe::orderBy('nom')->get();
        $classe_id = $request->get('classe_id');
        $trimestre = $request->get('trimestre', 1);
        $eleves    = collect();

        if ($classe_id) {
            $elevesClasse = Eleve::where('classe_id', $classe_id)
                                 ->where('statut', 'actif')
                                 ->get();

            $eleves = $elevesClasse->map(function ($eleve) use ($trimestre) {
                $notes = Note::with('matiere')
                    ->where('eleve_id', $eleve->id)
                    ->where('trimestre', 'trimestre' . $trimestre)
                    ->get();

                $totalCoefs      = $notes->sum(fn($n) => $n->matiere->coefficient);
                $totalPoints     = $notes->sum(fn($n) => $n->note * $n->matiere->coefficient);
                $eleve->moyenne  = $totalCoefs > 0 ? round($totalPoints / $totalCoefs, 2) : null;

                return $eleve;
            })->sortByDesc('moyenne')->values();
        }

        return view('notes.classement', compact('classes', 'classe_id', 'trimestre', 'eleves'));
    }

    public function bulletin(Request $request)
    {
        $request->validate([
            'eleve_id'  => 'required|exists:eleves,id',
            'trimestre' => 'required',
        ]);

        $eleve     = Eleve::with('classe')->findOrFail($request->eleve_id);
        $trimestre = $request->trimestre;

        // Convertir format numérique si besoin (1 -> trimestre1)
        if (is_numeric($trimestre)) {
            $trimestre = 'trimestre' . $trimestre;
        }

        $notes = Note::with('matiere')
            ->where('eleve_id', $eleve->id)
            ->where('trimestre', $trimestre)
            ->get();

        if ($notes->isEmpty()) {
            return back()->with('error', 'Aucune note trouvée pour cet élève ce trimestre.');
        }

        $totalPoints     = $notes->sum(fn($n) => $n->note * $n->matiere->coefficient);
        $totalCoefs      = $notes->sum(fn($n) => $n->matiere->coefficient);
        $moyenneGenerale = $totalCoefs > 0 ? $totalPoints / $totalCoefs : 0;

        $mention = match(true) {
            $moyenneGenerale >= 16 => 'Excellent',
            $moyenneGenerale >= 14 => 'Très bien',
            $moyenneGenerale >= 12 => 'Bien',
            $moyenneGenerale >= 10 => 'Assez bien',
            default                => 'Passable',
        };

        // Calcul du rang dans la classe
        $tousLesEleves = Eleve::where('classe_id', $eleve->classe_id)
                               ->where('statut', 'actif')->get();

        $moyennes = $tousLesEleves->map(function ($e) use ($trimestre) {
            $ns = Note::with('matiere')
                ->where('eleve_id', $e->id)
                ->where('trimestre', $trimestre)->get();
            $tc = $ns->sum(fn($n) => $n->matiere->coefficient);
            $tp = $ns->sum(fn($n) => $n->note * $n->matiere->coefficient);
            return ['id' => $e->id, 'moy' => $tc > 0 ? $tp / $tc : 0];
        })->sortByDesc('moy')->values();

        $rang        = $moyennes->search(fn($m) => $m['id'] === $eleve->id) + 1;
        $totalEleves = $tousLesEleves->count();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('notes.bulletin', compact(
            'eleve', 'notes', 'trimestre', 'moyenneGenerale',
            'totalPoints', 'totalCoefs', 'mention', 'rang', 'totalEleves'
        ))->setPaper('a4', 'portrait');

        return $pdf->download("bulletin_{$eleve->matricule}_{$trimestre}.pdf");
    }
}
