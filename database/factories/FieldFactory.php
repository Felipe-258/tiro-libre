<?php

namespace Database\Factories;

use App\Models\Field;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FieldFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Field::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $start = '08:00:00';
        $end = '00:00:00';

        return [
            'id_owner' => User::factory(),
            'coordinates' => $this->faker->randomElement(['-33.009503525403844, -58.55017110578025','-33.016412942668595, -58.52227613187237','-33.01821218123133, -58.51266309471026','-33.00558534143462, -58.507109237790466']),
            'name' => $this->faker->colorName(),
            'capacity' => $this->faker->numberBetween(5,11),
            'type' => $this->faker->randomElement(['Pasto Sintetico','Pasto Natural','Arena', 'Tierra', 'Cemento']),
            'price' => $this->faker->numberBetween(7000,2000),
            'description' => $this->faker->paragraph(),
            'start' => $start,
            'end' => $end,
        ];
    }
}