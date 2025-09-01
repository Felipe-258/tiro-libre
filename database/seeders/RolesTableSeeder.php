<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Field;
use Spatie\Permission\Models\Permission;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        Role::create(['name' => 'owner', 'guard_name' => 'web']);
        Role::create(['name' => 'player', 'guard_name' => 'web']);
        $superAdmin = Role::create(['name' => 'super-admin', 'guard_name' => 'web']);

        $permissions = Permission::all();
        $superAdmin->syncPermissions($permissions);

        //Apartado para crear Superadmins
        $superAdmin1 = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('12345678'),
            'surname' => 'Admin',
            'phone' => '54912345678',
        ]);
        $superAdmin1->assignRole($superAdmin);

        $superAdmin2 = User::create([
            'name' => 'Super Admin 2',
            'email' => 'superadmin2@example.com',
            'password' => bcrypt('12345678'),
            'surname' => 'Super Admin 2',
            'phone' => '54912345678',
        ]);
        $superAdmin2->assignRole($superAdmin);

        $superAdmin3 = User::create([
            'name' => 'Super Admin 3',
            'email' => 'superadmin3@example.com',
            'password' => bcrypt('12345678'),
            'surname' => 'Super Admin 3',
            'phone' => '54912345678',
        ]);
        $superAdmin3->assignRole($superAdmin);
         
        $canchero = User::create([
            'name' => 'Canchero',
            'email' => 'canchero@example.com',
            'password' => bcrypt('12345678'),
            'surname' => 'Canchero',
            'phone' => '1234567890',
        ]);
        $ownerRole = Role::where('name', 'owner')->first();
        $canchero->assignRole($ownerRole);

        $jugador = User::create([
            'name' => 'Jugador',
            'email' => 'jugador@example.com',
            'password' => bcrypt('12345678'),
            'surname' => 'Jugador',
            'phone' => '0987654321',
        ]);
        $playerRole = Role::where('name', 'player')->first();
        $jugador->assignRole($playerRole);

        // Crear una cancha de prueba para el canchero de prueba
        $canchaPrueba = Field::create([
            'id_owner' => $canchero->id,
            'coordinates' => '-33.000000, -58.500000',
            'name' => 'Cancha de Prueba',
            'capacity' => 10,
            'type' => 'Césped Sintético',
            'price' => 15000,
            'description' => 'Cancha de prueba generada por el seeder. Puedes gestionar esta cancha desde canchero@example.com.',
            'start' => '08:00:00',
            'end' => '23:00:00',
        ]);
    }
}
