<?php

namespace App\Http\Controllers;

use App\Models\Eleve;
use App\Models\Note;
use Illuminate\Http\Request;

class BulletinController extends Controller
{
    public function show(Eleve $eleve, $trimestre)
    {
        $eleve->load('classe');

        if (!$eleve->classe) {
            abort(404, 'Classe introuvable pour cet élève.');
        }

        $notes = Note::with('matiere')
            ->where('eleve_id', $eleve->id)
            ->where('trimestre', $trimestre)
            ->orderBy('created_at')
            ->get();

        $totalCoefs = 0;
        $totalPoints = 0;

        foreach ($notes as $note) {
            if (!$note->matiere) {
                continue;
            }
            $coef = $note->matiere->coefficient ?? 1;
            $totalCoefs += $coef;
            $totalPoints += ($note->note * $coef);
        }

        $moyenneGenerale = $totalCoefs > 0
            ? round($totalPoints / $totalCoefs, 2)
            : 0;

        $tousLesElevesClasse = Eleve::where('classe_id', $eleve->classe_id)
            ->where('statut', 'actif')
            ->with(['notes' => function ($query) use ($trimestre) {
                $query->where('trimestre', $trimestre)->with('matiere');
            }])
            ->get();

        $toutes_les_moyennes = $tousLesElevesClasse->map(function ($e) {
            $totalCoef = 0;
            $totalPoints = 0;
            foreach ($e->notes as $note) {
                if (!$note->matiere) {
                    continue;
                }
                $coef = $note->matiere->coefficient ?? 1;
                $totalCoef += $coef;
                $totalPoints += ($note->note * $coef);
            }
            return [
                'id' => $e->id,
                'moyenne' => $totalCoef > 0 ? round($totalPoints / $totalCoef, 2) : 0
            ];
        })->sortByDesc('moyenne')->values();

        $rang = 1;
        foreach ($toutes_les_moyennes as $index => $item) {
            if ($item['id'] == $eleve->id) {
                $rang = $index + 1;
                break;
            }
        }

        $totalEleves = $tousLesElevesClasse->count();

        $mention = $this->getMention($moyenneGenerale);

        $bulletinNumero = strtoupper("BLT-{$eleve->matricule}-{$trimestre}");

        $ecole = config('ecole');

        return view('notes.bulletin', compact(
            'eleve',
            'notes',
            'trimestre',
            'moyenneGenerale',
            'rang',
            'totalEleves',
            'totalCoefs',
            'totalPoints',
            'mention',
            'bulletinNumero',
            'ecole'
        ));
    }

    private function getMention($moyenne)
    {
        if ($moyenne >= 16) return 'Excellent';
        if ($moyenne >= 14) return 'Très bien';
        if ($moyenne >= 12) return 'Bien';
        if ($moyenne >= 10) return 'Assez bien';
        return 'Insuffisant';
    }
}
