<?php

namespace App\Services;

use App\Models\Eleve;
use App\Models\Note;
use Illuminate\Support\Collection;

class MoyenneService
{
    /**
     * Compute weighted average from a collection of notes (each having ->matiere->coefficient).
     *
     * @return float|null  null when there are no coefficients
     */
    public static function computeFromNotes(Collection $notes): ?float
    {
        $totalCoefs  = $notes->sum(fn ($n) => $n->matiere->coefficient);
        $totalPoints = $notes->sum(fn ($n) => $n->note * $n->matiere->coefficient);

        return $totalCoefs > 0 ? round($totalPoints / $totalCoefs, 2) : null;
    }

    /**
     * Return the French "mention" label for a given average.
     */
    public static function mention(?float $moyenne): string
    {
        return match (true) {
            $moyenne === null       => '',
            $moyenne >= 16          => 'Excellent',
            $moyenne >= 14          => 'Très bien',
            $moyenne >= 12          => 'Bien',
            $moyenne >= 10          => 'Assez bien',
            default                 => 'Insuffisant',
        };
    }

    /**
     * Return the Bootstrap badge CSS class for a given mention.
     */
    public static function mentionBadgeClass(?float $moyenne): string
    {
        return match (true) {
            $moyenne === null       => '',
            $moyenne >= 16          => 'bg-success',
            $moyenne >= 14          => 'bg-primary',
            $moyenne >= 12          => 'bg-info',
            $moyenne >= 10          => 'bg-warning',
            default                 => 'bg-danger',
        };
    }

    /**
     * Load active students for a class, compute their weighted averages for a
     * given trimestre, and return them sorted by average descending.
     *
     * The trimestre value stored in the DB uses the "trimestre1" format.
     * Callers may pass either "1" or "trimestre1"; this method normalises it.
     *
     * @return \Illuminate\Support\Collection<Eleve>  each has a ->moyenne attribute
     */
    public static function elevesWithMoyenne(int $classeId, string|int $trimestre): Collection
    {
        $trimestreDb = self::normaliseTrimestre($trimestre);

        return Eleve::where('classe_id', $classeId)
            ->where('statut', 'actif')
            ->get()
            ->map(function (Eleve $eleve) use ($trimestreDb) {
                $notes = Note::with('matiere')
                    ->where('eleve_id', $eleve->id)
                    ->where('trimestre', $trimestreDb)
                    ->get();

                $eleve->moyenne = self::computeFromNotes($notes);

                return $eleve;
            })
            ->sortByDesc('moyenne')
            ->values();
    }

    /**
     * Compute rank (1-based) of a given student among a collection of students
     * for a trimestre. Also returns total student count.
     *
     * @return array{rang: int, totalEleves: int}
     */
    public static function computeRang(Eleve $eleve, string|int $trimestre): array
    {
        $trimestreDb = self::normaliseTrimestre($trimestre);

        $classeEleves = Eleve::where('classe_id', $eleve->classe_id)
            ->where('statut', 'actif')
            ->get();

        $moyennes = $classeEleves->map(function (Eleve $e) use ($trimestreDb) {
            $notes = Note::with('matiere')
                ->where('eleve_id', $e->id)
                ->where('trimestre', $trimestreDb)
                ->get();

            return [
                'id'  => $e->id,
                'moy' => self::computeFromNotes($notes) ?? 0,
            ];
        })->sortByDesc('moy')->values();

        $rang = $moyennes->search(fn ($m) => $m['id'] === $eleve->id);

        return [
            'rang'        => $rang !== false ? $rang + 1 : $classeEleves->count(),
            'totalEleves' => $classeEleves->count(),
        ];
    }

    /**
     * Normalise a trimestre value to the DB format ("trimestre1", etc.).
     */
    public static function normaliseTrimestre(string|int $trimestre): string
    {
        if (is_numeric($trimestre)) {
            return 'trimestre' . $trimestre;
        }

        return $trimestre;
    }
}
