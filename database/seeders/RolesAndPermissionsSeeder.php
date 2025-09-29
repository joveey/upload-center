<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat Permissions
        Permission::create(['name' => 'register format']);
        Permission::create(['name' => 'upload data']);
        Permission::create(['name' => 'view history']);

        // Buat Roles dan berikan permissions
        $role = Role::create(['name' => 'finance-admin']);
        $role->givePermissionTo(['upload data', 'view history']);

        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());
    }
}