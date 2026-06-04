<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $seen = [];

        foreach (DB::table('classe_enseignant')->orderBy('id')->get() as $row) {
            $key = $row->user_id . '-' . $row->classe_id;

            if (isset($seen[$key])) {
                DB::table('classe_enseignant')->where('id', $row->id)->delete();
            } else {
                $seen[$key] = true;
            }
        }

        Schema::table('classe_enseignant', function (Blueprint $table) {
            $table->unique(['user_id', 'classe_id'], 'classe_enseignant_user_classe_unique');
        });
    }

    public function down(): void
    {
        Schema::table('classe_enseignant', function (Blueprint $table) {
            $table->dropUnique('classe_enseignant_user_classe_unique');
        });
    }
};
