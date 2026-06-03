<?php
namespace App\Http\Controllers;

use App\Models\Classe;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    public function index()
    {
        $classes = Classe::withCount('eleves')->get();
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

        Classe::create($request->all());

        return redirect()->route('gestionnaire.classes.index')
                         ->with('success', 'Classe créée avec succès.');
    }

    public function edit(Classe $classe)
    {
        return view('classes.edit', compact('classe'));
    }

    public function update(Request $request, Classe $classe)
    {
        $request->validate([
            'nom'             => 'required|string|max:50|unique:classes,nom,'.$classe->id,
            'niveau'          => 'required|in:CP1,CP2,CE1,CE2,CM1,CM2',
            'frais_scolarite' => 'required|numeric|min:0',
        ]);

        $classe->update($request->all());

        return redirect()->route('gestionnaire.classes.index')
                         ->with('success', 'Classe modifiée avec succès.');
    }

    public function destroy(Classe $classe)
    {
        if ($classe->eleves()->count() > 0) {
            return redirect()->route('gestionnaire.classes.index')
                             ->with('error', 'Impossible de supprimer une classe avec des élèves.');
        }

        $classe->delete();

        return redirect()->route('gestionnaire.classes.index')
                         ->with('success', 'Classe supprimée avec succès.');
    }
}
