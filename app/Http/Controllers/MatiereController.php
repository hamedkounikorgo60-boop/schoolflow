<?php
namespace App\Http\Controllers;

use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MatiereController extends Controller
{
    public function index()
    {
        $enseignant = Auth::user()->enseignant;
        $matieres   = $enseignant
            ? $enseignant->matieres()->orderBy('nom')->get()
            : collect();

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

        try {
            $matiere = Matiere::create($request->only('nom', 'coefficient', 'niveau', 'filiere'));
        } catch (\Throwable $e) {
            Log::error('Échec de la création de la matière', ['error' => $e->getMessage()]);
            return back()->withInput()->withErrors(['general' => 'Erreur lors de la création de la matière.']);
        }

        $enseignant = Auth::user()->enseignant;
        if ($enseignant) {
            $enseignant->matieres()->syncWithoutDetaching([$matiere->id]);
        }

        return redirect()
            ->route('enseignant.matieres.index')
            ->with('success', 'Matière ajoutée et rattachée à votre profil.');
    }

    public function edit(Matiere $matiere)
    {
        $this->authorizeMatiere($matiere);

        return view('matieres.edit', compact('matiere'));
    }

    public function update(Request $request, Matiere $matiere)
    {
        $this->authorizeMatiere($matiere);

        $request->validate([
            'nom'         => 'required|string|max:255|unique:matieres,nom,' . $matiere->id,
            'coefficient' => 'required|integer|min:1',
            'niveau'      => 'required|string|max:100',
            'filiere'     => 'required|string|max:100',
        ]);

        try {
            $matiere->update($request->only('nom', 'coefficient', 'niveau', 'filiere'));
        } catch (\Throwable $e) {
            Log::error('Échec de la modification de la matière', ['id' => $matiere->id, 'error' => $e->getMessage()]);
            return back()->withInput()->withErrors(['general' => 'Erreur lors de la modification de la matière.']);
        }

        return redirect()
            ->route('enseignant.matieres.index')
            ->with('success', 'Matière modifiée.');
    }

    public function destroy(Matiere $matiere)
    {
        $this->authorizeMatiere($matiere);

        $enseignant = Auth::user()->enseignant;
        if ($enseignant) {
            $enseignant->matieres()->detach($matiere->id);
        }

        if ($matiere->notes()->count() === 0) {
            $matiere->delete();
            return redirect()
                ->route('enseignant.matieres.index')
                ->with('success', 'Matière supprimée de votre profil et du catalogue.');
        }

        return redirect()
            ->route('enseignant.matieres.index')
            ->with('success', 'Matière retirée de votre profil (conservée au catalogue car des notes y sont liées).');
    }

    private function authorizeMatiere(Matiere $matiere): void
    {
        $enseignant = Auth::user()->enseignant;

        abort_unless(
            $enseignant && $enseignant->matieres()->where('matieres.id', $matiere->id)->exists(),
            403
        );
    }
}
