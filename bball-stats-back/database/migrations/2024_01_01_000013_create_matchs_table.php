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
        Schema::create('matchs', function (Blueprint $table) {
            $table->increments('Id_Match');
            $table->date('date_match');
            $table->integer('numero');
            $table->boolean('domicile');
            $table->integer('score_f');
            $table->integer('score_a');
            $table->unsignedInteger('Id_Equipe');
            $table->unsignedInteger('Id_Saison');
            $table->foreign('Id_Equipe')->references('Id_Equipe')->on('equipes')->onDelete('cascade');
            $table->foreign('Id_Saison')->references('Id_Saison')->on('saisons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matchs');
    }
}; 