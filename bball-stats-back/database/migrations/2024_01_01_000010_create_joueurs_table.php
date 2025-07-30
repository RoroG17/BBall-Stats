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
        Schema::create('joueurs', function (Blueprint $table) {
            $table->string('licence')->primary();
            $table->string('nom');
            $table->string('prenom');
            $table->string('civilite');
            $table->date('date_naissance');
            $table->string('photo')->nullable();
            $table->unsignedInteger('Id_Equipe');
            $table->foreign('Id_Equipe')->references('Id_Equipe')->on('equipes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('joueurs');
    }
}; 