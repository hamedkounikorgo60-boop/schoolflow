<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Http\Requests\MatiereRequest;
use App\Models\Classe;
use App\Models\Matiere;
use Illuminate\Http\Request;

class MatiereController extends Controller
{
    public function index(Request $request)
    {
        $classeId = $request->integer('classe_id') ?: null;
        $classes  = Classe::orderBy('nom')->get();
        $classe   = $classeId ? $classes->firstWhere('id', $classeId) : null;

        $matieres = Matiere::withCount(['enseignants', 'notes'])
            ->forClasse($classeId)
            ->orderBy('nom')
            ->orderBy('niveau')
            ->get();

        return view('gestionnaire.matieres.index', compact('matieres', 'classes', 'classeId', 'classe'));
    }

    public function create(Request $request)
    {
        $classeId = $request->integer('classe_id') ?: null;
        $classe   = $classeId ? Classe::find($classeId) : null;

        return view('gestionnaire.matieres.create', compact('classe'));
    }

    public function store(MatiereRequest $request)
    {
        $request->validate([
            'classe_id' => 'nullable|exists:classes,id',
        ]);

        Matiere::create($request->safe()->only(['nom', 'coefficient', 'niveau', 'filiere']));

        $redirectParams = $request->filled('classe_id') ? ['classe_id' => $request->classe_id] : [];

        return redirect()
            ->route('gestionnaire.matieres.index', $redirectParams)
            ->with('success', 'Matière ajoutée au catalogue.');
    }

    public function edit(Matiere $matiere)
    {
        $matiere->loadCount('notes');

        return view('gestionnaire.matieres.edit', compact('matiere'));
    }

    public function update(MatiereRequest $request, Matiere $matiere)
    {
        $request->validate([
            'classe_id' => 'nullable|exists:classes,id',
        ]);

        $matiere->update($request->safe()->only(['nom', 'coefficient', 'niveau', 'filiere']));

        $redirectParams = $request->filled('classe_id') ? ['classe_id' => $request->classe_id] : [];

        return redirect()
            ->route('gestionnaire.matieres.index', $redirectParams)
            ->with('success', 'Matière modifiée.');
    }

    public function destroy(Matiere $matiere)
    {
        if ($matiere->notes()->exists()) {
            return redirect()
                ->route('gestionnaire.matieres.index', request()->only('classe_id'))
                ->with('error', "Impossible de supprimer « {$matiere->nom} » ({$matiere->niveau}) : des notes y sont liées.");
        }

        $matiere->delete();

        return redirect()
            ->route('gestionnaire.matieres.index', request()->only('classe_id'))
            ->with('success', 'Matière supprimée du catalogue.');
    }
}
