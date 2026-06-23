<?php
namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Eleve;
use App\Models\Matiere;
use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $classes   = Classe::all();
        $classe_id = $request->classe_id;
        $trimestre = $request->trimestre ?? 1;
        $eleves    = collect();

        if ($classe_id) {
            $eleves = Eleve::where('classe_id', $classe_id)
                ->with(['notes' => function($q) use ($trimestre) {
                    $q->where('trimestre', $trimestre)->with('matiere');
                }])
                ->get()
                ->map(function($eleve) {
                    $notes      = $eleve->notes->filter(fn($n) => $n->matiere !== null);
                    $somme      = $notes->sum(fn($n) => $n->note * $n->matiere->coefficient);
                    $totalCoeff = $notes->sum(fn($n) => $n->matiere->coefficient);
                    $eleve->moyenne = $totalCoeff > 0 ? round($somme / $totalCoeff, 2) : null;
                    return $eleve;
                })
                ->sortByDesc('moyenne');
        }

        return view('notes.index', compact('classes', 'eleves', 'classe_id', 'trimestre'));
    }

    public function create()
    {
        $classes  = Classe::all();
        $eleves   = Eleve::with('classe')->orderBy('nom')->get();
        $matieres = Matiere::all();
        return view('notes.create', compact('classes', 'eleves', 'matieres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'eleve_id'   => 'required|exists:eleves,id',
            'matiere_id' => 'required|exists:matieres,id',
            'note'       => 'required|numeric|min:0|max:20',
            'trimestre'  => 'required|in:1,2,3',
        ]);

        try {
            Note::updateOrCreate(
                [
                    'eleve_id'   => $request->eleve_id,
                    'matiere_id' => $request->matiere_id,
                    'trimestre'  => $request->trimestre,
                ],
                [
                    'note'          => $request->note,
                    'enseignant_id' => null,
                ]
            );
        } catch (\Throwable $e) {
            Log::error('Échec de l\'enregistrement de la note', ['error' => $e->getMessage()]);
            return back()->withInput()->withErrors(['general' => 'Erreur lors de l\'enregistrement de la note.']);
        }

        return redirect()->route('gestionnaire.notes.create')
                         ->with('success', 'Note enregistrée avec succès.');
    }

    public function classement(Request $request)
    {
        $classes   = Classe::all();
        $classe_id = $request->classe_id;
        $trimestre = $request->trimestre ?? 1;
        $eleves    = collect();

        if ($classe_id) {
            $eleves = Eleve::where('classe_id', $classe_id)
                ->with(['notes' => function($q) use ($trimestre) {
                    $q->where('trimestre', $trimestre)->with('matiere');
                }])
                ->get()
                ->map(function($eleve) {
                    $notes      = $eleve->notes->filter(fn($n) => $n->matiere !== null);
                    $somme      = $notes->sum(fn($n) => $n->note * $n->matiere->coefficient);
                    $totalCoeff = $notes->sum(fn($n) => $n->matiere->coefficient);
                    $eleve->moyenne = $totalCoeff > 0 ? round($somme / $totalCoeff, 2) : null;
                    return $eleve;
                })
                ->sortByDesc('moyenne')
                ->values();
        }

        return view('notes.classement', compact('classes', 'eleves', 'classe_id', 'trimestre'));
    }
}
