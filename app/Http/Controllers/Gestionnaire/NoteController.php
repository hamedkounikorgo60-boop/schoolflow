<?php
namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Eleve;
use App\Models\Classe;
use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Contrôleur de gestion des notes
 * Gère l'affichage, la création, la modification des notes et la génération des bulletins
 */
class NoteController extends Controller
{
    /**
     * Affiche la liste des notes par classe et trimestre
     * Calcule la moyenne générale pour chaque élève
     */
    public function index(Request $request)
    {
        // Récupère toutes les classes triées par nom
        $classes   = Classe::orderBy('nom')->get();
        
        // Récupère les paramètres de la requête (classe_id et trimestre)
        $classe_id = $request->get('classe_id');
        $trimestre = $request->get('trimestre', 1);
        
        // Initialise une collection vide pour les élèves
        $eleves    = collect();

        // Si une classe est sélectionnée
        if ($classe_id) {
            // Récupère les élèves actifs de la classe
            $elevesClasse = Eleve::where('classe_id', $classe_id)
                                 ->where('statut', 'actif')
                                 ->get();

            // Calcule la moyenne pour chaque élève
            $eleves = $elevesClasse->map(function ($eleve) use ($trimestre) {
                // Récupère les notes de l'élève pour le trimestre spécifié
                $notes = Note::with('matiere')
                    ->where('eleve_id', $eleve->id)
                    ->where('trimestre', 'trimestre' . $trimestre)
                    ->get();

                $validNotes = $notes->filter(fn($n) => $n->matiere !== null);

                // Calcule le total des coefficients et des points pondérés
                $totalCoefs      = $validNotes->sum(fn($n) => $n->matiere->coefficient);
                $totalPoints     = $validNotes->sum(fn($n) => $n->note * $n->matiere->coefficient);
                
                // Calcule la moyenne générale (points / coefficients)
                $eleve->moyenne  = $totalCoefs > 0 ? round($totalPoints / $totalCoefs, 2) : null;

                return $eleve;
            })->sortByDesc('moyenne')->values(); // Trie par moyenne décroissante
        }

        // Retourne la vue avec les données
        return view('notes.index', compact('classes', 'classe_id', 'trimestre', 'eleves'));
    }

    /**
     * Affiche le formulaire de création d'une note
     */
    public function create()
    {
        // Récupère tous les élèves actifs
        $eleves   = Eleve::where('statut', 'actif')->orderBy('nom')->get();
        
        // Récupère toutes les matières
        $matieres = Matiere::orderBy('nom')->get();
        
        return view('notes.create', compact('eleves', 'matieres'));
    }

    /**
     * Enregistre une note dans la base de données
     * Met à jour la note si elle existe déjà, sinon la crée
     */
    public function store(Request $request)
    {
        // Valide les données du formulaire
        $request->validate([
            'eleve_id'   => 'required|exists:eleves,id',  // L'élève doit exister
            'matiere_id' => 'required|exists:matieres,id', // La matière doit exister
            'note'       => 'required|numeric|min:0|max:20', // Note entre 0 et 20
            'trimestre'  => 'required|in:trimestre1,trimestre2,trimestre3', // Trimestre valide
        ]);

        try {
            // Crée ou met à jour la note
            Note::updateOrCreate(
                [
                    'eleve_id'   => $request->eleve_id,
                    'matiere_id' => $request->matiere_id,
                    'trimestre'  => $request->trimestre,
                ],
                [
                    'note' => $request->note,
                ]
            );
        } catch (\Throwable $e) {
            Log::error('Échec de l\'enregistrement de la note', ['error' => $e->getMessage()]);
            return back()->withInput()->withErrors(['general' => 'Erreur lors de l\'enregistrement de la note.']);
        }

        // Redirige avec un message de succès
        return redirect()->route('gestionnaire.notes.index')
                         ->with('success', 'Note enregistrée avec succès.');
    }

    /**
     * Affiche le classement des élèves par moyenne
     */
    public function classement(Request $request)
    {
        // Récupère toutes les classes
        $classes   = Classe::orderBy('nom')->get();
        $classe_id = $request->get('classe_id');
        $trimestre = $request->get('trimestre', 1);
        $eleves    = collect();

        // Si une classe est sélectionnée, calcule le classement
        if ($classe_id) {
            $elevesClasse = Eleve::where('classe_id', $classe_id)
                                 ->where('statut', 'actif')
                                 ->get();

            // Calcule la moyenne pour chaque élève et trie par ordre décroissant
            $eleves = $elevesClasse->map(function ($eleve) use ($trimestre) {
                $notes = Note::with('matiere')
                    ->where('eleve_id', $eleve->id)
                    ->where('trimestre', 'trimestre' . $trimestre)
                    ->get();

                $validNotes = $notes->filter(fn($n) => $n->matiere !== null);

                $totalCoefs      = $validNotes->sum(fn($n) => $n->matiere->coefficient);
                $totalPoints     = $validNotes->sum(fn($n) => $n->note * $n->matiere->coefficient);
                $eleve->moyenne  = $totalCoefs > 0 ? round($totalPoints / $totalCoefs, 2) : null;

                return $eleve;
            })->sortByDesc('moyenne')->values();
        }

        return view('notes.classement', compact('classes', 'classe_id', 'trimestre', 'eleves'));
    }

    /**
     * Génère le bulletin de notes d'un élève en PDF
     * Affiche les notes, la moyenne, le rang et la mention
     */
    public function bulletin(Request $request)
    {
        // Valide les données
        $request->validate([
            'eleve_id'  => 'required|exists:eleves,id',
            'trimestre' => 'required',
        ]);

        // Récupère l'élève avec sa classe
        $eleve     = Eleve::with('classe')->findOrFail($request->eleve_id);

        if (!$eleve->classe) {
            return back()->with('error', 'Cet élève n\'est associé à aucune classe.');
        }

        $trimestre = $request->trimestre;

        // Convertit le numéro de trimestre en format "trimestre1", "trimestre2", etc.
        if (is_numeric($trimestre)) {
            $trimestre = 'trimestre' . $trimestre;
        }

        // Récupère les notes de l'élève pour le trimestre spécifié
        $notes = Note::with('matiere')
            ->where('eleve_id', $eleve->id)
            ->where('trimestre', $trimestre)
            ->get();

        // Si pas de notes, affiche un message d'erreur
        if ($notes->isEmpty()) {
            return back()->with('error', 'Aucune note trouvée pour cet élève ce trimestre.');
        }

        $validNotes = $notes->filter(fn($n) => $n->matiere !== null);

        if ($validNotes->isEmpty()) {
            return back()->with('error', 'Aucune note avec une matière valide trouvée pour cet élève ce trimestre.');
        }

        // Calcule la moyenne générale
        $totalPoints     = $validNotes->sum(fn($n) => $n->note * $n->matiere->coefficient);
        $totalCoefs      = $validNotes->sum(fn($n) => $n->matiere->coefficient);
        $moyenneGenerale = $totalCoefs > 0 ? $totalPoints / $totalCoefs : 0;

        // Détermine la mention en fonction de la moyenne
        $mention = match(true) {
            $moyenneGenerale >= 16 => 'Excellent',
            $moyenneGenerale >= 14 => 'Très bien',
            $moyenneGenerale >= 12 => 'Bien',
            $moyenneGenerale >= 10 => 'Assez bien',
            default                => 'Passable',
        };

        // Calcule le rang de l'élève dans la classe
        $tousLesEleves = Eleve::where('classe_id', $eleve->classe_id)
                               ->where('statut', 'actif')->get();

        $moyennes = $tousLesEleves->map(function ($e) use ($trimestre) {
            $ns = Note::with('matiere')
                ->where('eleve_id', $e->id)
                ->where('trimestre', $trimestre)->get();
            $validNs = $ns->filter(fn($n) => $n->matiere !== null);
            $tc = $validNs->sum(fn($n) => $n->matiere->coefficient);
            $tp = $validNs->sum(fn($n) => $n->note * $n->matiere->coefficient);
            return ['id' => $e->id, 'moy' => $tc > 0 ? $tp / $tc : 0];
        })->sortByDesc('moy')->values();

        $searchResult = $moyennes->search(fn($m) => $m['id'] === $eleve->id);
        $rang         = $searchResult !== false ? $searchResult + 1 : $tousLesEleves->count();
        $totalEleves  = $tousLesEleves->count();

        // Générer le numéro de bulletin
        $trimestreShort = str_replace('trimestre', '', $trimestre);
        $bulletinNumero = 'BLT-' . $eleve->matricule . '-' . $trimestreShort;

        // Récupérer les infos école
        $ecole = config('ecole');

        try {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('notes.bulletin', compact(
                'eleve', 'notes', 'trimestre', 'moyenneGenerale',
                'totalPoints', 'totalCoefs', 'mention', 'rang', 'totalEleves',
                'bulletinNumero', 'ecole'
            ))->setPaper('a4', 'portrait');

            return $pdf->download("bulletin_{$eleve->matricule}_{$trimestre}.pdf");
        } catch (\Throwable $e) {
            Log::error('Échec de la génération du bulletin PDF', [
                'eleve_id' => $eleve->id,
                'error'    => $e->getMessage(),
            ]);
            return back()->with('error', 'Erreur lors de la génération du bulletin PDF.');
        }
    }
}
