<?php
namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Eleve;
use App\Models\Classe;
use App\Models\Matiere;
use App\Services\MoyenneService;
use Illuminate\Http\Request;

/**
 * Contrôleur de gestion des notes
 * Gère l'affichage, la création, la modification des notes et la génération des bulletins
 */
class NoteController extends Controller
{
    public function index(Request $request)
    {
        $classes   = Classe::orderBy('nom')->get();
        $classe_id = $request->get('classe_id');
        $trimestre = $request->get('trimestre', 1);
        $eleves    = $classe_id
            ? MoyenneService::elevesWithMoyenne((int) $classe_id, $trimestre)
            : collect();

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
        $eleves    = $classe_id
            ? MoyenneService::elevesWithMoyenne((int) $classe_id, $trimestre)
            : collect();

        return view('notes.classement', compact('classes', 'classe_id', 'trimestre', 'eleves'));
    }

    public function bulletin(Request $request)
    {
        $request->validate([
            'eleve_id'  => 'required|exists:eleves,id',
            'trimestre' => 'required',
        ]);

        $eleve     = Eleve::with('classe')->findOrFail($request->eleve_id);
        $trimestre = MoyenneService::normaliseTrimestre($request->trimestre);

        $notes = Note::with('matiere')
            ->where('eleve_id', $eleve->id)
            ->where('trimestre', $trimestre)
            ->get();

        if ($notes->isEmpty()) {
            return back()->with('error', 'Aucune note trouvée pour cet élève ce trimestre.');
        }

        $totalPoints     = $notes->sum(fn($n) => $n->note * $n->matiere->coefficient);
        $totalCoefs      = $notes->sum(fn($n) => $n->matiere->coefficient);
        $moyenneGenerale = MoyenneService::computeFromNotes($notes) ?? 0;
        $mention         = MoyenneService::mention($moyenneGenerale);

        $ranking     = MoyenneService::computeRang($eleve, $trimestre);
        $rang        = $ranking['rang'];
        $totalEleves = $ranking['totalEleves'];

        $trimestreShort = str_replace('trimestre', '', $trimestre);
        $bulletinNumero = 'BLT-' . $eleve->matricule . '-' . $trimestreShort;

        $ecole = config('ecole');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('notes.bulletin', compact(
            'eleve', 'notes', 'trimestre', 'moyenneGenerale',
            'totalPoints', 'totalCoefs', 'mention', 'rang', 'totalEleves',
            'bulletinNumero', 'ecole'
        ))->setPaper('a4', 'portrait');

        return $pdf->download("bulletin_{$eleve->matricule}_{$trimestre}.pdf");
    }
}
