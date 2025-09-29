<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Division;
use Spatie\Permission\Models\Role;

class AssignRoleToExistingUsers extends Seeder
{
    public function run(): void
    {
        // Pastikan roles sudah ada
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $divisionUserRole = Role::firstOrCreate(['name' => 'division-user']);

        // Buat division contoh jika belum ada
        $division = Division::firstOrCreate(
            ['name' => 'Finance'],
            ['name' => 'Finance']
        );

        // Get semua user yang belum punya role
        $users = User::all();

        foreach ($users as $user) {
            // Update division_id jika masih null
            if (!$user->division_id) {
                $user->division_id = $division->id;
                $user->save();
            }

            // Assign role jika belum punya role
            if (!$user->hasAnyRole(['super-admin', 'division-user'])) {
                // User pertama jadi super-admin, sisanya jadi division-user
                if ($user->id === 1) {
                    $user->assignRole($superAdminRole);
                    echo "User {$user->name} assigned as super-admin\n";
                } else {
                    $user->assignRole($divisionUserRole);
                    echo "User {$user->name} assigned as division-user\n";
                }
            }
        }

        echo "All users have been assigned roles!\n";
    }
}