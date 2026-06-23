<?php
namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Eleve;
use App\Models\Classe;
use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function create(Request $request)
    {
        $user       = Auth::user();
        $enseignant = $user->enseignant;
        $classes    = $user->classes()->orderBy('nom')->get()->unique('id')->values();
        $classeId  = $request->integer('classe_id') ?: null;
        $matiereId = $request->integer('matiere_id') ?: null;

        $matieresQuery = $enseignant ? $enseignant->matieres() : null;
        $matieres      = $matieresQuery
            ? $matieresQuery->forClasse($classeId)->orderBy('nom')->get()
            : collect();

        if ($matiereId && ! $matieres->contains('id', $matiereId)) {
            $matiereId = null;
        }
        $trimestre = $request->get('trimestre', 'trimestre1');

        $eleves          = collect();
        $notesExistantes = [];

        if ($classeId && $this->ownsClasse($classeId)) {
            $eleves = Eleve::with('classe')
                ->where('classe_id', $classeId)
                ->where('statut', 'actif')
                ->orderBy('nom')
                ->orderBy('prenoms')
                ->get();

            if ($matiereId && $this->ownsMatiere($matiereId)) {
                $notesExistantes = Note::where('matiere_id', $matiereId)
                    ->where('trimestre', $trimestre)
                    ->whereIn('eleve_id', $eleves->pluck('id'))
                    ->pluck('note', 'eleve_id')
                    ->all();
            }
        }

        return view('enseignant.notes.create', compact(
            'classes',
            'matieres',
            'classeId',
            'matiereId',
            'trimestre',
            'eleves',
            'notesExistantes'
        ));
    }

    public function storeBulk(Request $request)
    {
        $request->validate([
            'classe_id'   => 'required|exists:classes,id',
            'matiere_id'  => 'required|exists:matieres,id',
            'trimestre'   => 'required|in:trimestre1,trimestre2,trimestre3',
            'notes'       => 'required|array',
            'notes.*'     => 'nullable|numeric|min:0|max:20',
        ]);

        $this->ownsClasse($request->classe_id) || abort(403);
        $this->ownsMatiere($request->matiere_id) || abort(403);

        $enseignant = Auth::user()->enseignant;
        $eleveIds   = Eleve::where('classe_id', $request->classe_id)
            ->where('statut', 'actif')
            ->pluck('id');

        $saved = 0;

        foreach ($request->notes as $eleveId => $noteValue) {
            if (! $eleveIds->contains((int) $eleveId)) {
                continue;
            }
            if ($noteValue === null || $noteValue === '') {
                continue;
            }

            Note::updateOrCreate(
                [
                    'eleve_id'   => $eleveId,
                    'matiere_id' => $request->matiere_id,
                    'trimestre'  => $request->trimestre,
                ],
                [
                    'note'          => $noteValue,
                    'enseignant_id' => optional($enseignant)->id,
                ]
            );
            $saved++;
        }

        if ($saved === 0) {
            return back()
                ->withInput()
                ->with('error', 'Aucune note à enregistrer. Renseignez au moins une note.');
        }

        return redirect()
            ->route('enseignant.notes.create', [
                'classe_id'  => $request->classe_id,
                'matiere_id' => $request->matiere_id,
                'trimestre'  => $request->trimestre,
            ])
            ->with('success', "{$saved} note(s) enregistrée(s) avec succès.");
    }

    public function store(Request $request)
    {
        $request->validate([
            'eleve_id'   => 'required|exists:eleves,id',
            'matiere_id' => 'required|exists:matieres,id',
            'note'       => 'required|numeric|min:0|max:20',
            'trimestre'  => 'required|in:trimestre1,trimestre2,trimestre3',
        ]);

        $eleve = Eleve::findOrFail($request->eleve_id);
        $this->ownsClasse($eleve->classe_id) || abort(403);
        $this->ownsMatiere($request->matiere_id) || abort(403);

        $enseignant = Auth::user()->enseignant;

        Note::updateOrCreate(
            [
                'eleve_id'   => $request->eleve_id,
                'matiere_id' => $request->matiere_id,
                'trimestre'  => $request->trimestre,
            ],
            [
                'note'          => $request->note,
                'enseignant_id' => optional($enseignant)->id,
            ]
        );

        return redirect()->route('enseignant.dashboard')
            ->with('success', 'Note enregistrée avec succès.');
    }

    public function index(Request $request)
    {
        $classes   = Auth::user()->classes()->orderBy('nom')->get()->unique('id')->values();
        $classe_id = $request->get('classe_id');
        $trimestre = $request->get('trimestre', 1);
        $eleves    = collect();

        if ($classe_id && $this->ownsClasse((int) $classe_id)) {
            $elevesClasse = Eleve::where('classe_id', $classe_id)
                ->where('statut', 'actif')
                ->get();

            $eleves = $elevesClasse->map(function ($eleve) use ($trimestre) {
                $notes = Note::with('matiere')
                    ->where('eleve_id', $eleve->id)
                    ->where('trimestre', 'trimestre' . $trimestre)
                    ->get();

                $totalCoefs     = $notes->sum(fn ($n) => $n->matiere->coefficient);
                $totalPoints    = $notes->sum(fn ($n) => $n->note * $n->matiere->coefficient);
                $eleve->moyenne = $totalCoefs > 0 ? round($totalPoints / $totalCoefs, 2) : null;

                return $eleve;
            })->sortByDesc('moyenne')->values();
        }

        return view('enseignant.notes.index', compact('classes', 'classe_id', 'trimestre', 'eleves'));
    }

    private function ownsClasse(int $classeId): bool
    {
        return Auth::user()->classes()->where('classes.id', $classeId)->exists();
    }

    private function ownsMatiere(int $matiereId): bool
    {
        $enseignant = Auth::user()->enseignant;

        return $enseignant
            && $enseignant->matieres()->where('matieres.id', $matiereId)->exists();
    }
}
