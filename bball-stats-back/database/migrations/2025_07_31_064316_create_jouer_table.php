<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJouerTable extends Migration
{
    public function up(): void
    {
        Schema::create('jouer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('Id_Match');
            $table->string('licence');
            $table->integer('minutes')->nullable();

            foreach (range(1, 4) as $q) {
                $table->integer("q{$q}_passes_decisives")->nullable();
                $table->integer("q{$q}_rebonds_offensifs")->nullable();
                $table->integer("q{$q}_rebonds_defensifs")->nullable();
                $table->integer("q{$q}_interceptions")->nullable();
                $table->integer("q{$q}_contres")->nullable();
                $table->integer("q{$q}_ballons_perdus")->nullable();
                $table->integer("q{$q}_fautes")->nullable();
                $table->integer("q{$q}_tirs_2pts_reussis")->nullable();
                $table->integer("q{$q}_tirs_2pts_manques")->nullable();
                $table->integer("q{$q}_tirs_3pts_reussis")->nullable();
                $table->integer("q{$q}_tirs_3pts_manques")->nullable();
                $table->integer("q{$q}_lancers_francs_reussis")->nullable();
                $table->integer("q{$q}_lancers_francs_rates")->nullable();
                $table->integer("q{$q}_passes_reussies")->nullable();
                $table->integer("q{$q}_passes_rates")->nullable();
            }

            // Clés étrangères (à adapter si les tables cibles existent déjà)
            $table->foreign('Id_Match')->references('Id_Match')->on('matchs');
            $table->foreign('licence')->references('licence')->on('joueurs');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jouer');
    }
}
