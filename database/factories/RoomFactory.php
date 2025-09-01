<?php

namespace Database\Factories;

use App\Models\Turn;
use App\Models\room;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = room::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
         // Obtener un ID de turno existente
        $turnId = turn::inRandomOrder()->first()->id;

        return [
            'id_turn' => $turnId,
            'name' => $this->faker->unique()->colorName(),
            'quantity' => $this->faker->numberBetween(1, 100),
            'max' => $this->faker->numberBetween(1, 100),
            'description' => $this->faker->paragraph(),
        ];
    }
}
