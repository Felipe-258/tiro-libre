<?php

namespace Database\Factories;

use App\Models\Turn;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Field;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TurnFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Turn::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Obtener una colección de todos los IDs de campo disponibles
        $fieldIds = Field::pluck('id');
    
        // Seleccionar un ID de campo aleatorio de la colección
        $fieldId = $this->faker->randomElement($fieldIds);
    
        // Obtener un ID de usuario que no se haya utilizado anteriormente en un registro de turno
        $userId = DB::table('users')
                    ->whereNotIn('id', Turn::pluck('id_player')->unique())
                    ->inRandomOrder()
                    ->value('id');
    
        return [
            'id_player' => $userId,
            'id_field' => $fieldId,
            'day' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
/*             'duration' => $this->faker->numberBetween(1, 24),
 */        ];
    }
}
