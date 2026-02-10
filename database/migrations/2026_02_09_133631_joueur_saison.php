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
        Schema::create('participer', function (Blueprint $table) {
            $table->unsignedInteger('Id_Saison');
            $table->string('licence',8);
            $table->foreign('Id_Saison')->references('Id_Saison')->on('saisons')->onDelete('cascade');
            $table->foreign('licence')->references('licence')->on('joueurs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participer');
    }
};
