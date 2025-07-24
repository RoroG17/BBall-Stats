<?php

namespace Database\Factories;

use App\Models\Joueur;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class JoueurFactory extends Factory
{
    protected $model = Joueur::class;

    public function definition()
    {
        return [
            'licence' => strtoupper(Str::random(8)),
            'nom' => $this->faker->lastName,
            'prenom' => $this->faker->firstName,
            'civilite' => $this->faker->randomElement(['M', 'F']),
            'date_naissance' => $this->faker->date('Y-m-d', '2010-01-01'),
            'photo' => $this->faker->optional()->imageUrl(200, 200, 'people'),
        ];
    }
} 