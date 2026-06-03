<?php
namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Classe;
use App\Models\Matiere;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user      = Auth::user();
        $enseignant = $user->enseignant;

        // Notes saisies par cet enseignant
        $notesCount = Note::where('enseignant_id', optional($enseignant)->id)->count();

        // Matières liées à cet enseignant (via la table matieres si enseignant_id existe)
        $matieres = Matiere::all();

        // Classes distinctes où il a saisi des notes
        $classesIds = Note::where('enseignant_id', optional($enseignant)->id)
                          ->with('eleve.classe')
                          ->get()
                          ->pluck('eleve.classe_id')
                          ->unique();

        $nbClasses = $classesIds->count();

        // Dernières notes saisies
        $dernieresNotes = Note::with(['eleve', 'matiere'])
                              ->where('enseignant_id', optional($enseignant)->id)
                              ->latest()
                              ->take(8)
                              ->get();

        return view('enseignant.dashboard', compact(
            'user', 'enseignant', 'notesCount', 'matieres', 'nbClasses', 'dernieresNotes'
        ));
    }
}
