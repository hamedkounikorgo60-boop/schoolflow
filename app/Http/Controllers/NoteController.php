<?php
namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Eleve;
use App\Models\Matiere;
use App\Models\Classe;
use App\Services\MoyenneService;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $classes   = Classe::all();
        $classe_id = $request->classe_id;
        $trimestre = $request->trimestre ?? 1;
        $eleves    = $classe_id
            ? MoyenneService::elevesWithMoyenne((int) $classe_id, $trimestre)
            : collect();

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

        return redirect()->route('gestionnaire.notes.create')
                         ->with('success', 'Note enregistrée avec succès.');
    }

    public function classement(Request $request)
    {
        $classes   = Classe::all();
        $classe_id = $request->classe_id;
        $trimestre = $request->trimestre ?? 1;
        $eleves    = $classe_id
            ? MoyenneService::elevesWithMoyenne((int) $classe_id, $trimestre)
            : collect();

        return view('notes.classement', compact('classes', 'eleves', 'classe_id', 'trimestre'));
    }
}
