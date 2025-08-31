<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\turn;

class TurnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        turn::factory(50)->create();
    }
}
