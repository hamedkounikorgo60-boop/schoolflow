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
        Schema::create('eleves', function (Blueprint $table) {
            $table->id();
            $table->string('matricule')->unique();
$table->string('nom');
$table->string('prenoms');
$table->date('date_naissance');
$table->string('lieu_naissance');
$table->string('genre');
$table->string('telephone')->nullable();
$table->string('adresse')->nullable();
$table->string('email')->nullable();
$table->unsignedBigInteger('classe_id');
$table->string('redoublant')->default('non');
$table->string('statut')->default('actif');
$table->timestamps();

$table->foreign('classe_id')->references('id')->on('classes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eleves');
    }
};
