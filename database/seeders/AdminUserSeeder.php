<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Support\Permissions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $adminRole = Role::firstOrCreate([
            'name' => Permissions::SUPERADMIN_ROLE,
            'guard_name' => 'web',
        ]);

        $adminUser = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Super Admin',
                'user_type' => Permissions::SUPERADMIN_ROLE,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $adminUser->user_type = Permissions::SUPERADMIN_ROLE;
        $adminUser->syncAssignedRole($adminRole);
    }
}
