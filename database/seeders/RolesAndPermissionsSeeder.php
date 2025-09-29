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
        Permission::create(['name' => 'register format']); // Izin untuk membuat format baru
        Permission::create(['name' => 'upload data']);     // Izin untuk mengunggah data
        Permission::create(['name' => 'view all formats']);// Izin KHUSUS untuk melihat semua format dari semua divisi

        // --- BUAT PERAN (ROLES) ---

        // 1. Peran untuk Pengguna Divisi (misal: Finance, Logistik, dll.)
        // Mereka bisa membuat format dan mengunggah data untuk divisinya.
        $divisionUserRole = Role::create(['name' => 'division-user']);
        $divisionUserRole->givePermissionTo(['register format', 'upload data']);

        // 2. Peran untuk Super Admin
        // Dia bisa melakukan segalanya, termasuk melihat semua format.
        $superAdminRole = Role::create(['name' => 'super-admin']);
        $superAdminRole->givePermissionTo(Permission::all());
    }
}