<?php

namespace Database\Seeders;

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
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // --- BUAT SEMUA IZIN (PERMISSIONS) ---
        Permission::firstOrCreate(['name' => 'register format']);
        Permission::firstOrCreate(['name' => 'upload data']);
        Permission::firstOrCreate(['name' => 'view all formats']);
        Permission::firstOrCreate(['name' => 'create mapping']);

        // --- BUAT PERAN (ROLES) ---

        // 1. Peran untuk Pengguna Divisi
        $divisionUserRole = Role::firstOrCreate(['name' => 'division-user']);
        // Tambahkan 'create mapping' ke dalam izin untuk peran ini
        $divisionUserRole->syncPermissions(['register format', 'upload data', 'create mapping']);

        // 2. Peran untuk Super Admin
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        // Super admin otomatis mendapatkan semua izin
        $superAdminRole->syncPermissions(Permission::all());

        echo "Roles and permissions seeded successfully!\n";
    }
}