<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OwnerTurns>
 */
class OwnerTurnsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_owner' => $this->faker->numberBetween(1, 12),
            'id_field' => $this->faker->numberBetween(1, 12),
            'day' => $this->faker->dateTimeBetween('-1 month', '+6 month'),
            'player' =>$this->faker->firstName(),
        ];
    }
}
