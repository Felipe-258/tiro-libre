<?php

namespace Database\Factories;

use App\Models\PlayerRoom;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlayerRoomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PlayerRoom::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_room' => $this->faker->unique()->randomNumber(),
            'id_player' => $this->faker->unique()->randomNumber(),
        ];
    }
}
