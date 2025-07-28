<?php

namespace Database\Seeders;

use App\Models\Equipe;
use App\Models\User;
use App\Models\Joueur;
use App\Models\Matchs;
use App\Models\Saison;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Joueur::factory(20)->create();
        Equipe::factory(20)->create();
        Saison::factory(5)->create();
        Matchs::factory(20)->create();
    }
}
