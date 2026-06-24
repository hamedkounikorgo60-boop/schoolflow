<?php
namespace App\Http\Controllers;

use App\Http\Requests\EleveRequest;
use App\Models\Eleve;
use App\Models\Classe;
use Illuminate\Support\Facades\Storage;

class EleveController extends Controller
{
    public function index()
    {
        $eleves = Eleve::with('classe')->latest()->paginate(10);
        return view('eleves.index', compact('eleves'));
    }

    public function create()
    {
        $classes = Classe::all();
        return view('eleves.create', compact('classes'));
    }

    public function store(EleveRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')
                                     ->store('photos', 'public');
        }

        $data['redoublant'] = $request->has('redoublant') ? 1 : 0;

        Eleve::create($data);

        return redirect()->route('gestionnaire.eleves.index')
                         ->with('success', 'Élève inscrit avec succès.');
    }

    public function show(Eleve $eleve)
    {
        $eleve->load('classe', 'paiements', 'notes.matiere');
        return view('eleves.show', compact('eleve'));
    }

    public function edit(Eleve $eleve)
    {
        $classes = Classe::all();
        return view('eleves.edit', compact('eleve', 'classes'));
    }

    public function update(EleveRequest $request, Eleve $eleve)
    {
        $data = $request->validated();
        $data['redoublant'] = $request->has('redoublant') ? 1 : 0;

        if ($request->hasFile('photo')) {
            if ($eleve->photo) {
                Storage::disk('public')->delete($eleve->photo);
            }
            $data['photo'] = $request->file('photo')
                                     ->store('photos', 'public');
        } else {
            unset($data['photo']);
        }

        $eleve->update($data);

        return redirect()->route('gestionnaire.eleves.index')
                         ->with('success', 'Élève modifié avec succès.');
    }

    public function destroy(Eleve $eleve)
    {
        if ($eleve->photo) {
            Storage::disk('public')->delete($eleve->photo);
        }
        $eleve->delete();

        return redirect()->route('gestionnaire.eleves.index')
                         ->with('success', 'Élève supprimé avec succès.');
    }
}
