<?php

namespace Database\Factories;

use App\Models\Saison;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaisonFactory extends Factory
{
    protected $model = Saison::class;

    public function definition()
    {
        $annee_debut = $this->faker->numberBetween(2000, 2030);
        return [
            'annee_debut' => $annee_debut,
            'annee_fin' => $annee_debut + 1,
            'championnat' => $this->faker->randomElement(['National 1', 'National 2', 'Régional', 'Départemental']),
            'categorie' => $this->faker->randomElement(['U9', 'U11', 'U13', 'U15', 'U18', 'U20', 'Sénior']),
        ];
    }
} 