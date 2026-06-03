<?php
namespace App\Http\Controllers;

use App\Models\Matiere;
use Illuminate\Http\Request;

class MatiereController extends Controller
{
    public function index()
    {
        $matieres = Matiere::latest()->get();
        return view('matieres.index', compact('matieres'));
    }

    public function create()
    {
        return view('matieres.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'         => 'required|string|max:255|unique:matieres',
            'coefficient' => 'required|integer|min:1',
            'niveau'      => 'required|string|max:100',
            'filiere'     => 'required|string|max:100',
        ]);

        Matiere::create($request->only('nom', 'coefficient', 'niveau', 'filiere'));

        return redirect()
            ->route('enseignant.matieres.index')
            ->with('success', 'Matière ajoutée avec succès.');
    }

    public function edit(Matiere $matiere)
    {
        return view('matieres.edit', compact('matiere'));
    }

    public function update(Request $request, Matiere $matiere)
    {
        $request->validate([
            'nom'         => 'required|string|max:255|unique:matieres,nom,' . $matiere->id,
            'coefficient' => 'required|integer|min:1',
            'niveau'      => 'required|string|max:100',
            'filiere'     => 'required|string|max:100',
        ]);

        $matiere->update($request->only('nom', 'coefficient', 'niveau', 'filiere'));

        return redirect()
            ->route('enseignant.matieres.index')
            ->with('success', 'Matière modifiée.');
    }

    public function destroy(Matiere $matiere)
    {
        $matiere->delete();
        return redirect()
            ->route('enseignant.matieres.index')
            ->with('success', 'Matière supprimée.');
    }
}
