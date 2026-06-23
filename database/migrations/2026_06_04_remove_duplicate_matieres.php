<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Trouver les doublons et garder seulement le premier ID pour chaque nom
        $duplicates = DB::table('matieres')
            ->selectRaw('nom, MIN(id) as keep_id')
            ->groupBy('nom')
            ->having(DB::raw('COUNT(*)'), '>', 1)
            ->get();

        foreach ($duplicates as $dup) {
            // Récupérer tous les IDs des doublons
            $duplicateIds = DB::table('matieres')
                ->where('nom', $dup->nom)
                ->where('id', '!=', $dup->keep_id)
                ->pluck('id')
                ->toArray();

            if (!empty($duplicateIds)) {
                // Remplacer les références dans les tables de pivot si elles existent
                if (Schema::hasTable('enseignant_matiere')) {
                    DB::table('enseignant_matiere')
                        ->whereIn('matiere_id', $duplicateIds)
                        ->update(['matiere_id' => $dup->keep_id]);
                }

                if (Schema::hasTable('classe_matiere')) {
                    DB::table('classe_matiere')
                        ->whereIn('matiere_id', $duplicateIds)
                        ->update(['matiere_id' => $dup->keep_id]);
                }

                // Supprimer les doublons
                DB::table('matieres')
                    ->whereIn('id', $duplicateIds)
                    ->delete();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Les données supprimées ne peuvent pas être restaurées
        // Cette migration est destructive
    }
};
