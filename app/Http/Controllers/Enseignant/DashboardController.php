<?php
namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user       = Auth::user();
        $enseignant = $user->enseignant;

        $classes  = $user->classes()->orderBy('nom')->get()->unique('id')->values();
        $matieres = $enseignant
            ? $enseignant->matieres()->orderBy('nom')->get()
            : collect();

        $enseignantId = $enseignant ? $enseignant->id : null;

        $notesCount = $enseignantId
            ? Note::where('enseignant_id', $enseignantId)->count()
            : 0;
        $nbClasses  = $classes->count();

        $dernieresNotes = $enseignantId
            ? Note::with(['eleve.classe', 'matiere'])
                ->where('enseignant_id', $enseignantId)
                ->latest()
                ->take(8)
                ->get()
            : collect();

        return view('enseignant.dashboard', compact(
            'user',
            'enseignant',
            'notesCount',
            'matieres',
            'classes',
            'nbClasses',
            'dernieresNotes'
        ));
    }
}
