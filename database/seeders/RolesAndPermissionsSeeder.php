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
        // Gunakan firstOrCreate agar tidak error jika sudah ada
        Permission::firstOrCreate(['name' => 'register format']);
        Permission::firstOrCreate(['name' => 'upload data']);
        Permission::firstOrCreate(['name' => 'view all formats']);

        // --- BUAT PERAN (ROLES) ---

        // 1. Peran untuk Pengguna Divisi (misal: Finance, Logistik, dll.)
        $divisionUserRole = Role::firstOrCreate(['name' => 'division-user']);
        $divisionUserRole->syncPermissions(['register format', 'upload data']);

        // 2. Peran untuk Super Admin
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdminRole->syncPermissions(Permission::all());

        echo "Roles and permissions seeded successfully!\n";
    }
}