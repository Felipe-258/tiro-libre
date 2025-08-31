<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Field;


class FieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $start = '08:00:00';
        $end = '23:00:00';

        // Conjunto de datos fijos para diferentes canchas
        $fields = [
            [
                'coordinates' => '-33.010076365323286, -58.549900840928146',
                'name' => 'Canchas Luly Ríos',
                'capacity' => 12,
                'type' => 'Cesped Sintetico',        /* Luli Rios F6 */  
                'price' => 12000,
                'description' => 'Complejo destinado a la recreacion deportiva que posee tambien salones para desarrollar encuentros familiares , de amigos y todo evento recreativo.',
            ],
            [
                'coordinates' => '-33.010076365323286, -58.549900840928146',
                'name' => 'Canchas Luly Ríos',
                'capacity' => 10,
                'type' => 'Cesped Sintetico',                   /* Luli Rios F5 */
                'price' => 10000,
                'description' => 'Complejo destinado a la recreacion deportiva que posee tambien salones para desarrollar encuentros familiares , de amigos y todo evento recreativo.',
            ],
            [
                'coordinates' => '-33.01675237831998, -58.521169536943134',
                'name' => 'Cartagena Spot Club & Bar',
                'capacity' => 12,                   /* Cartagena F6 */
                'type' => 'Cesped Sintetico',
                'price' => 20000,
                'description' => 'Cancha de Fútbol 6, Patio cervecero, Cumpleaños, Eventos, 3446624490 3446615007, Av. Rocamora 538 - Gualeguaychú.',
            ],
            [
                'coordinates' => '-33.01655125014322, -58.52154011784165',
                'name' => 'Cartagena Spot Club & Bar',
                'capacity' => 10,
                'type' => 'Cesped Sintetico',                     /* Cartagena F5 */
                'price' => 18000,
                'description' => 'Cancha de Fútbol 5, Patio cervecero, Cumpleaños, Eventos, 3446624490 3446615007',
            ],
            [
                'coordinates' => '-33.005633976074876, -58.50713822740675',
                'name' => 'Gol en Contra',
                'capacity' => 10,
                'type' => 'Cesped Sintetico',                     /*Gol Encontra*/
                'price' => 10000,
                'description' => 'Cancha de Fútbol 5',
            ],
            [
                'coordinates' => '-33.018204169899704, -58.51280238827032',
                'name' => 'Tercer Tiempo canchas de futbol y hockey',
                'capacity' => 12,
                'type' => 'Cesped Sintetico',                     /*Tercer Tiempo canchas de futbol y hockey F6*/
                'price' => 30000,
                'description' => 'Tercer Tiempo es un complejo deportivo y recreativo que consta de canchas de pasto sintético de hockey y de futbol 6 y 7.Estamos en Avenida Parque Cándido Irazusta 652 (entre Montevideo y Pellegrini)',
            ],
            [
                'coordinates' => '-33.018204169899704, -58.51280238827032',
                'name' => 'Tercer Tiempo canchas de futbol y hockey',
                'capacity' => 14,
                'type' => 'Cesped Sintetico',                     /*Tercer Tiempo canchas de futbol y hockey F7*/
                'price' => 35000,
                'description' => 'Tercer Tiempo es un complejo deportivo y recreativo que consta de canchas de pasto sintético de hockey y de futbol 6 y 7.Estamos en Avenida Parque Cándido Irazusta 652 (entre Montevideo y Pellegrini)',
            ],
            [
                'coordinates' => '-33.00755349098757, -58.528114052272436',
                'name' => 'Ritual F5 Juventud Cancha 1',
                'capacity' => 10,
                'type' => 'Cesped Sintetico',                     /*Ritual F5 Juventud Cancha 1*/
                'price' => 20000,
                'description' => 'Ritual F5 es ese ritual que comienza cuando a vos te pintan las ganas de jugar al futbol (todas las semanas).',
            ],
            [
                'coordinates' => '-33.00755349098757, -58.528114052272436',
                'name' => 'Ritual F5 Juventud Cancha 2',
                'capacity' => 10,
                'type' => 'Cesped Sintetico',                     /*Ritual F5 Juventud Cancha 2*/
                'price' => 20000,
                'description' => 'Ritual F5 es ese ritual que comienza cuando a vos te pintan las ganas de jugar al futbol (todas las semanas).',
            ],
            [
                'coordinates' => '-32.9900321753148, -58.52277527158547',
                'name' => 'Futbol 5 Paso A Paso',
                'capacity' => 10,
                'type' => 'Cesped Sintetico',                     /*Futbol 5 Paso A Paso*/
                'price' => 20000,
                'description' => 'CANCHA TECHADA - TURNOS FIJOS - ESTACIONAMIENTO PRIVADO - CUMPLEAÑOS, REUNIONES - TORNEOS - CESPED SINTETICO 50mm C/ARENA Y CAUCHO - ESTOMBA 2234 - Tel: 4884388 - SMS: 2914134495',
            ],
            [
                'coordinates' => '-33.0051019247714, -58.515219924402686',
                'name' => 'Futbol 5 Independiente',
                'capacity' => 10,
                'type' => 'Cesped Sintetico',                     /*Futbol 5 Independiente*/
                'price' => 20000,
                'description' => 'Campo de fútbol 5',
            ],
            [
                'coordinates' => '-33.02391670488152, -58.50918282640103',
                'name' => 'Futbol 5 Pueblo Nuevo',
                'capacity' => 10,
                'type' => 'Cesped Sintetico',                     /*Futbol 5 Pueblo Nuevo*/
                'price' => 20000,
                'description' => 'Campo de fútbol 5',
            ],
        
        ];

        foreach ($fields as $field) {
            Field::create(array_merge($field, [
                'id_owner' => rand(1, 10),  // Crear y asociar un usuario aleatorio
                'start' => '08:00:00',                      // Hora fija de inicio
                'end' => '23:00:00',                        // Hora fija de fin
            ]));
        }
    }
}

