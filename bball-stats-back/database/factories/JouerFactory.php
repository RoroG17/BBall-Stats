<?php

namespace Database\Factories;

use App\Models\Joueur;
use App\Models\Matchs;
use Illuminate\Database\Eloquent\Factories\Factory;

class JouerFactory extends Factory
{
    public function definition(): array
    {
        $data = [
            'Id_Match' => Matchs::factory(),
            'licence' => Joueur::factory(),
            'minutes' => $this->faker->numberBetween(0, 40),
        ];

        foreach (range(1, 4) as $q) {
            $data["q{$q}_passes_decisives"] = $this->faker->numberBetween(0, 10);
            $data["q{$q}_rebonds_offensifs"] = $this->faker->numberBetween(0, 10);
            $data["q{$q}_rebonds_defensifs"] = $this->faker->numberBetween(0, 10);
            $data["q{$q}_interceptions"] = $this->faker->numberBetween(0, 5);
            $data["q{$q}_contres"] = $this->faker->numberBetween(0, 5);
            $data["q{$q}_ballons_perdus"] = $this->faker->numberBetween(0, 5);
            $data["q{$q}_fautes"] = $this->faker->numberBetween(0, 5);
            $data["q{$q}_tirs_2pts_reussis"] = $this->faker->numberBetween(0, 10);
            $data["q{$q}_tirs_2pts_manques"] = $this->faker->numberBetween(0, 10);
            $data["q{$q}_tirs_3pts_reussis"] = $this->faker->numberBetween(0, 5);
            $data["q{$q}_tirs_3pts_manques"] = $this->faker->numberBetween(0, 5);
            $data["q{$q}_lancers_francs_reussis"] = $this->faker->numberBetween(0, 10);
            $data["q{$q}_lancers_francs_rates"] = $this->faker->numberBetween(0, 10);
            $data["q{$q}_passes_reussies"] = $this->faker->numberBetween(0, 20);
            $data["q{$q}_passes_rates"] = $this->faker->numberBetween(0, 10);
        }

        return $data;
    }
}
