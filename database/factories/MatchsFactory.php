<?php

namespace Database\Factories;

use App\Models\Matchs;
use App\Models\Equipe;
use App\Models\Saison;
use Illuminate\Database\Eloquent\Factories\Factory;

class MatchsFactory extends Factory
{
    protected $model = Matchs::class;

    public function definition()
    {
        return [
            'date_match' => $this->faker->date(),
            'numero' => $this->faker->unique()->numberBetween(1, 100),
            'score_domicile' => $this->faker->numberBetween(40, 120),
            'score_exterieur' => $this->faker->numberBetween(40, 120),
            'equipe_domicile' => Equipe::factory(),
            'equipe_exterieur' => Equipe::factory(),
            'Id_Saison' => Saison::factory(),
        ];
    }
} 