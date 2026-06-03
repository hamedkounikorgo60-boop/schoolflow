<?php
namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Eleve;
use App\Models\Classe;
use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function create()
    {
        $eleves   = Eleve::where('statut', 'actif')->orderBy('nom')->get();
        $matieres = Matiere::orderBy('nom')->get();
        $classes  = Classe::orderBy('nom')->get();
        return view('enseignant.notes.create', compact('eleves', 'matieres', 'classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'eleve_id'   => 'required|exists:eleves,id',
            'matiere_id' => 'required|exists:matieres,id',
            'note'       => 'required|numeric|min:0|max:20',
            'trimestre'  => 'required|in:trimestre1,trimestre2,trimestre3',
        ]);

        $enseignant = Auth::user()->enseignant;

        Note::updateOrCreate(
            [
                'eleve_id'   => $request->eleve_id,
                'matiere_id' => $request->matiere_id,
                'trimestre'  => $request->trimestre,
            ],
            [
                'note'          => $request->note,
                'enseignant_id' => optional($enseignant)->id,
            ]
        );

        return redirect()->route('enseignant.dashboard')
                         ->with('success', 'Note enregistrée avec succès.');
    }

    public function index(Request $request)
    {
        $enseignant = Auth::user()->enseignant;
        $classes    = Classe::orderBy('nom')->get();
        $classe_id  = $request->get('classe_id');
        $trimestre  = $request->get('trimestre', 1);
        $eleves     = collect();

        if ($classe_id) {
            $elevesClasse = Eleve::where('classe_id', $classe_id)
                                 ->where('statut', 'actif')->get();

            $eleves = $elevesClasse->map(function ($eleve) use ($trimestre) {
                $notes = Note::with('matiere')
                    ->where('eleve_id', $eleve->id)
                    ->where('trimestre', 'trimestre' . $trimestre)
                    ->get();

                $totalCoefs     = $notes->sum(fn($n) => $n->matiere->coefficient);
                $totalPoints    = $notes->sum(fn($n) => $n->note * $n->matiere->coefficient);
                $eleve->moyenne = $totalCoefs > 0 ? round($totalPoints / $totalCoefs, 2) : null;

                return $eleve;
            })->sortByDesc('moyenne')->values();
        }

        return view('enseignant.notes.index', compact('classes', 'classe_id', 'trimestre', 'eleves'));
    }
}
