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

        $notes = Note::with('matiere')
            ->where('eleve_id', $eleve->id)
            ->where('trimestre', $trimestre)
            ->get();

        $totalCoef = 0;
        $totalPoints = 0;

        foreach ($notes as $note) {
            $coef = $note->matiere->coefficient ?? 1;

            $totalCoef += $coef;
            $totalPoints += ($note->note * $coef);
        }

        $moyenne = $totalCoef > 0
            ? round($totalPoints / $totalCoef, 2)
            : 0;

        $rang = 1; // à améliorer plus tard avec le classement réel

        return view('bulletins.show', compact(
            'eleve',
            'notes',
            'trimestre',
            'moyenne',
            'rang'
        ));
    }
}
