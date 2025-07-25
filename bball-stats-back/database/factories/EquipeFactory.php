<?php

namespace Database\Factories;

use App\Models\Equipe;
use Illuminate\Database\Eloquent\Factories\Factory;

class EquipeFactory extends Factory
{
    protected $model = Equipe::class;

    public function definition()
    {
        return [
            'nom' => $this->faker->company . ' Basket',
            'logo' => $this->faker->optional()->imageUrl(200, 200, 'sports'),
        ];
    }
} 