<?php

namespace Database\Seeders;

use App\Models\OwnerTurns;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OwnerTurnsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OwnerTurns::factory(10)->create();
    }
}
