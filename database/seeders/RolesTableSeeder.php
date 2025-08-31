<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
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
            'name' => 'Super Admin 1',
            'email' => 'superadmin1@example.com',
            'password' => bcrypt('12345678'),
            'surname' => 'Super Admin 1',
            'phone' => '5491125276194',
        ]);
        $superAdmin1->assignRole($superAdmin);

        $superAdmin2 = User::create([
            'name' => 'Super Admin 2',
            'email' => 'superadmin2@example.com',
            'password' => bcrypt('12345678'),
            'surname' => 'Super Admin 2',
            'phone' => '5493446610972',
        ]);
        $superAdmin2->assignRole($superAdmin);

        $superAdmin3 = User::create([
            'name' => 'Super Admin 3',
            'email' => 'superadmin3@example.com',
            'password' => bcrypt('12345678'),
            'surname' => 'Super Admin 3',
            'phone' => '5493446362878',
        ]);
        $superAdmin3->assignRole($superAdmin);
    }
}
