<?php
namespace App\Http\Controllers;

use App\Models\Eleve;
use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

    public function store(Request $request)
    {
        $request->validate([
            'matricule'       => 'required|unique:eleves',
            'nom'             => 'required|string|max:100',
            'prenoms'         => 'required|string|max:100',
            'date_naissance'  => 'required|date',
            'lieu_naissance'  => 'required|string|max:100',
            'genre'           => 'required|in:M,F',
            'classe_id'       => 'required|exists:classes,id',
            'telephone'       => 'nullable|string|max:20',
            'adresse'         => 'nullable|string|max:255',
            'photo'           => 'nullable|image|max:2048',
            'redoublant'      => 'boolean',
            'statut'          => 'required|in:actif,inactif',
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            if ($path === false) {
                return back()->withInput()->withErrors(['photo' => 'Échec du téléchargement de la photo.']);
            }
            $data['photo'] = $path;
        }

        $data['redoublant'] = $request->has('redoublant') ? 1 : 0;

        try {
            Eleve::create($data);
        } catch (\Throwable $e) {
            Log::error('Échec de la création de l\'élève', ['error' => $e->getMessage()]);
            return back()->withInput()->withErrors(['general' => 'Erreur lors de l\'inscription de l\'élève.']);
        }

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

    public function update(Request $request, Eleve $eleve)
    {
        $request->validate([
            'matricule'       => 'required|unique:eleves,matricule,'.$eleve->id,
            'nom'             => 'required|string|max:100',
            'prenoms'         => 'required|string|max:100',
            'date_naissance'  => 'required|date',
            'lieu_naissance'  => 'required|string|max:100',
            'genre'           => 'required|in:M,F',
            'classe_id'       => 'required|exists:classes,id',
            'telephone'       => 'nullable|string|max:20',
            'adresse'         => 'nullable|string|max:255',
            'photo'           => 'nullable|image|max:2048',
            'statut'          => 'required|in:actif,inactif',
        ]);

        $data = $request->except('photo');
        $data['redoublant'] = $request->has('redoublant') ? 1 : 0;

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            if ($path === false) {
                return back()->withInput()->withErrors(['photo' => 'Échec du téléchargement de la photo.']);
            }
            if ($eleve->photo) {
                Storage::disk('public')->delete($eleve->photo);
            }
            $data['photo'] = $path;
        }

        try {
            $eleve->update($data);
        } catch (\Throwable $e) {
            Log::error('Échec de la modification de l\'élève', ['id' => $eleve->id, 'error' => $e->getMessage()]);
            return back()->withInput()->withErrors(['general' => 'Erreur lors de la modification de l\'élève.']);
        }

        return redirect()->route('gestionnaire.eleves.index')
                         ->with('success', 'Élève modifié avec succès.');
    }

    public function destroy(Eleve $eleve)
    {
        try {
            if ($eleve->photo) {
                Storage::disk('public')->delete($eleve->photo);
            }
            $eleve->delete();
        } catch (\Throwable $e) {
            Log::error('Échec de la suppression de l\'élève', ['id' => $eleve->id, 'error' => $e->getMessage()]);
            return redirect()->route('gestionnaire.eleves.index')
                             ->with('error', 'Erreur lors de la suppression de l\'élève.');
        }

        return redirect()->route('gestionnaire.eleves.index')
                         ->with('success', 'Élève supprimé avec succès.');
    }
}
