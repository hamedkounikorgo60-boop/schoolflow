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
        Schema::create('matieres', function (Blueprint $table) {
    $table->id();
    $table->string('nom');              // Nom de la matière (Maths, Français…)
    $table->integer('coefficient');     // Coefficient numérique
    $table->string('niveau');           // Niveau scolaire (CP1, CE2…)
    $table->string('filiere');          // Filière ou section
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matieres');
    }
};
