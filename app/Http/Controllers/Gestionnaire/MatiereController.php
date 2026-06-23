<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    public function store(Request $request)
    {
        $request->validate([
            'nom'         => 'required|string|max:255|unique:matieres',
            'coefficient' => 'required|integer|min:1',
            'niveau'      => 'required|string|max:100',
            'filiere'     => 'required|string|max:100',
            'classe_id'   => 'nullable|exists:classes,id',
        ]);

        try {
            Matiere::create($request->only('nom', 'coefficient', 'niveau', 'filiere'));
        } catch (\Throwable $e) {
            Log::error('Échec de la création de la matière', ['error' => $e->getMessage()]);
            return back()->withInput()->withErrors(['general' => 'Erreur lors de la création de la matière.']);
        }

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

    public function update(Request $request, Matiere $matiere)
    {
        $request->validate([
            'nom'         => 'required|string|max:255|unique:matieres,nom,' . $matiere->id,
            'coefficient' => 'required|integer|min:1',
            'niveau'      => 'required|string|max:100',
            'filiere'     => 'required|string|max:100',
            'classe_id'   => 'nullable|exists:classes,id',
        ]);

        try {
            $matiere->update($request->only('nom', 'coefficient', 'niveau', 'filiere'));
        } catch (\Throwable $e) {
            Log::error('Échec de la modification de la matière', ['id' => $matiere->id, 'error' => $e->getMessage()]);
            return back()->withInput()->withErrors(['general' => 'Erreur lors de la modification de la matière.']);
        }

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

        try {
            $matiere->delete();
        } catch (\Throwable $e) {
            Log::error('Échec de la suppression de la matière', ['id' => $matiere->id, 'error' => $e->getMessage()]);
            return redirect()
                ->route('gestionnaire.matieres.index', request()->only('classe_id'))
                ->with('error', 'Erreur lors de la suppression de la matière.');
        }

        return redirect()
            ->route('gestionnaire.matieres.index', request()->only('classe_id'))
            ->with('success', 'Matière supprimée du catalogue.');
    }
}
