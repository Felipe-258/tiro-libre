<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
        
            UserSeeder::class,
            FieldSeeder::class,
            PlayerRoomSeeder::class,
            RatingSeeder::class,
            TurnSeeder::class,
            RoomSeeder::class,
            RolesTableSeeder::class,
            OwnerTurnsSeeder::class,

            // Agrega aquí otros seeders si los tienes
        ]);
    }
}
