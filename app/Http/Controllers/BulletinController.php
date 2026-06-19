<?php

namespace App\Http\Controllers;

use App\Models\Eleve;
use App\Models\Note;
use App\Services\MoyenneService;
use Illuminate\Http\Request;

class BulletinController extends Controller
{
    public function show(Eleve $eleve, $trimestre)
    {
        $eleve->load('classe');

        $notes = Note::with('matiere')
            ->where('eleve_id', $eleve->id)
            ->where('trimestre', $trimestre)
            ->orderBy('created_at')
            ->get();

        $totalCoefs  = $notes->sum(fn ($n) => $n->matiere->coefficient ?? 1);
        $totalPoints = $notes->sum(fn ($n) => $n->note * ($n->matiere->coefficient ?? 1));

        $moyenneGenerale = MoyenneService::computeFromNotes($notes) ?? 0;
        $mention         = MoyenneService::mention($moyenneGenerale);

        $ranking     = MoyenneService::computeRang($eleve, $trimestre);
        $rang        = $ranking['rang'];
        $totalEleves = $ranking['totalEleves'];

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
}
