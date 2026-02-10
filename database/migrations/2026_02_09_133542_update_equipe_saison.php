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
        Schema::table('saisons', function (Blueprint $table) {
            $table->unsignedInteger('equipe')->nullable();
            $table->foreign('equipe')->references('Id_Equipe')->on('equipes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saisons', function (Blueprint $table) {
            $table->dropForeign(['equipe']);
            $table->dropColumn('equipe');
        });
    }
};
