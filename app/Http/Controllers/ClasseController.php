<?php
namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClasseController extends Controller
{
    public function index()
    {
        $classes = Classe::withCount(['eleves', 'enseignants'])->get();
        return view('classes.index', compact('classes'));
    }

    public function create()
    {
        return view('classes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'             => 'required|string|max:50|unique:classes',
            'niveau'          => 'required|in:CP1,CP2,CE1,CE2,CM1,CM2',
            'frais_scolarite' => 'required|numeric|min:0',
        ]);

        try {
            Classe::create($request->all());
        } catch (\Throwable $e) {
            Log::error('Échec de la création de la classe', ['error' => $e->getMessage()]);
            return back()->withInput()->withErrors(['general' => 'Erreur lors de la création de la classe.']);
        }

        return redirect()->route('gestionnaire.classes.index')
                         ->with('success', 'Classe créée avec succès.');
    }

    public function edit(Classe $classe)
    {
        $enseignants = User::where('role', 'enseignant')->orderBy('name')->get();
        $assignedIds = $classe->enseignants()->pluck('users.id')->all();

        return view('classes.edit', compact('classe', 'enseignants', 'assignedIds'));
    }

    public function update(Request $request, Classe $classe)
    {
        $request->validate([
            'nom'             => 'required|string|max:50|unique:classes,nom,'.$classe->id,
            'niveau'          => 'required|in:CP1,CP2,CE1,CE2,CM1,CM2',
            'frais_scolarite' => 'required|numeric|min:0',
            'enseignant_ids'  => 'nullable|array',
            'enseignant_ids.*'=> 'exists:users,id',
        ]);

        try {
            $classe->update($request->only('nom', 'niveau', 'frais_scolarite'));
            $classe->enseignants()->sync($request->input('enseignant_ids', []));
        } catch (\Throwable $e) {
            Log::error('Échec de la modification de la classe', ['id' => $classe->id, 'error' => $e->getMessage()]);
            return back()->withInput()->withErrors(['general' => 'Erreur lors de la modification de la classe.']);
        }

        return redirect()->route('gestionnaire.classes.index')
                         ->with('success', 'Classe modifiée avec succès.');
    }

    public function destroy(Classe $classe)
    {
        if ($classe->eleves()->count() > 0) {
            return redirect()->route('gestionnaire.classes.index')
                             ->with('error', 'Impossible de supprimer une classe avec des élèves.');
        }

        try {
            $classe->delete();
        } catch (\Throwable $e) {
            Log::error('Échec de la suppression de la classe', ['id' => $classe->id, 'error' => $e->getMessage()]);
            return redirect()->route('gestionnaire.classes.index')
                             ->with('error', 'Erreur lors de la suppression de la classe.');
        }

        return redirect()->route('gestionnaire.classes.index')
                         ->with('success', 'Classe supprimée avec succès.');
    }
}
