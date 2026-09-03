<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Support\Permissions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class NormalUserSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $userRole = Role::firstOrCreate([
            'name' => Permissions::USER_ROLE,
            'guard_name' => 'web',
        ]);

        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Normal User',
                'user_type' => Permissions::USER_ROLE,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $user->user_type = Permissions::USER_ROLE;
        $user->syncAssignedRole($userRole);
    }
}
