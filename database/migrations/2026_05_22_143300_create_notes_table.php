<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('notes', function (Blueprint $table) {
        $table->id();

        $table->foreignId('eleve_id')
              ->constrained('eleves')
              ->onDelete('cascade');

        $table->foreignId('matiere_id')
              ->constrained('matieres')
              ->onDelete('cascade');

        $table->foreignId('enseignant_id')
              ->constrained('enseignants')
              ->onDelete('cascade');

        $table->float('note');
        $table->string('trimestre');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     * ooo
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
