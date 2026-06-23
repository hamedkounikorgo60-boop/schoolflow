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
        // Ajouter les matières pour CE1 et CE2
        $matieres = [
            ['nom' => 'Mathématiques', 'coefficient' => 3, 'niveau' => 'CE1', 'filiere' => 'General'],
            ['nom' => 'Français', 'coefficient' => 3, 'niveau' => 'CE1', 'filiere' => 'General'],
            ['nom' => 'Sciences', 'coefficient' => 2, 'niveau' => 'CE1', 'filiere' => 'General'],
            ['nom' => 'Histoire-Géo', 'coefficient' => 1, 'niveau' => 'CE1', 'filiere' => 'General'],
            ['nom' => 'Anglais', 'coefficient' => 2, 'niveau' => 'CE1', 'filiere' => 'General'],
            
            ['nom' => 'Mathématiques', 'coefficient' => 3, 'niveau' => 'CE2', 'filiere' => 'General'],
            ['nom' => 'Français', 'coefficient' => 3, 'niveau' => 'CE2', 'filiere' => 'General'],
            ['nom' => 'Sciences', 'coefficient' => 2, 'niveau' => 'CE2', 'filiere' => 'General'],
            ['nom' => 'Histoire-Géo', 'coefficient' => 1, 'niveau' => 'CE2', 'filiere' => 'General'],
            ['nom' => 'Anglais', 'coefficient' => 2, 'niveau' => 'CE2', 'filiere' => 'General'],
        ];

        foreach ($matieres as $matiere) {
            DB::table('matieres')->insertOrIgnore(array_merge($matiere, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('matieres')
            ->whereIn('niveau', ['CE1', 'CE2'])
            ->delete();
    }
};
