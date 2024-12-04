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
        /*$role = Role::firstOrCreate(['name' => 'client', 'guard_name' => 'web']);
        $permission = Permission::firstOrCreate(['name' => 'view dashboard', 'guard_name' => 'web']);

        $role->givePermissionTo($permission);*/
        // Create the 'client' role and permission
        $clientRole = Role::firstOrCreate(['name' => 'client', 'guard_name' => 'web']);
        $viewDashboardPermission = Permission::firstOrCreate(['name' => 'view dashboard', 'guard_name' => 'web']);
        $clientRole->givePermissionTo($viewDashboardPermission);

        // Create the 'waiter' role and permissions
        $waiterRole = Role::firstOrCreate(['name' => 'waiter', 'guard_name' => 'web']);

        $manageOrdersPermission = Permission::firstOrCreate(['name' => 'manage orders', 'guard_name' => 'web']);
        $serveTablesPermission = Permission::firstOrCreate(['name' => 'serve tables', 'guard_name' => 'web']);

        // Assign permissions to the 'waiter' role
        $waiterRole->givePermissionTo($manageOrdersPermission);
        $waiterRole->givePermissionTo($serveTablesPermission);
    }
}
