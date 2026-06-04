<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enseignant_matiere', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enseignant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('matiere_id')->constrained()->cascadeOnDelete();
            $table->unique(['enseignant_id', 'matiere_id']);
            $table->timestamps();
        });

        $enseignants = DB::table('enseignants')->pluck('id');
        $matiereIds  = DB::table('matieres')->pluck('id');

        foreach ($enseignants as $enseignantId) {
            $fromNotes = DB::table('notes')
                ->where('enseignant_id', $enseignantId)
                ->distinct()
                ->pluck('matiere_id');

            $ids = $fromNotes->isNotEmpty() ? $fromNotes : $matiereIds;

            foreach ($ids as $matiereId) {
                DB::table('enseignant_matiere')->insertOrIgnore([
                    'enseignant_id' => $enseignantId,
                    'matiere_id'    => $matiereId,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }

        $enseignantUsers = DB::table('enseignants')->select('id', 'user_id')->get();

        foreach ($enseignantUsers as $row) {
            $classeIds = DB::table('notes')
                ->join('eleves', 'eleves.id', '=', 'notes.eleve_id')
                ->where('notes.enseignant_id', $row->id)
                ->distinct()
                ->pluck('eleves.classe_id');

            foreach ($classeIds as $classeId) {
                DB::table('classe_enseignant')->insertOrIgnore([
                    'user_id'    => $row->user_id,
                    'classe_id'  => $classeId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('enseignant_matiere');
    }
};
