<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::firstOrCreate(['name' => 'client', 'guard_name' => 'web']);
        $permission = Permission::firstOrCreate(['name' => 'view dashboard', 'guard_name' => 'web']);

        $role->givePermissionTo($permission);
    }
}
