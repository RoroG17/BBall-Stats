<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Joueur;
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
    }
}
