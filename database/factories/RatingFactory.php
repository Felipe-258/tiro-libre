<?php

namespace Database\Factories;

use App\Models\Rating;
use App\Models\User;
use App\Models\Field;
use Illuminate\Database\Eloquent\Factories\Factory;

class RatingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Rating::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Obtener un jugador y un campo existentes de la base de datos
        $playerId = User::inRandomOrder()->first()->id;
        $fieldId = Field::inRandomOrder()->first()->id;

        return [
            'id_player' => $playerId,
            'id_field' => $fieldId,
            'rating' => $this->faker->randomFloat(2, 0, 10),
        ];
    }
}
