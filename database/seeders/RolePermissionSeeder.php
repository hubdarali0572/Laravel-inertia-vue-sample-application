<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\Permissions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->seedPermissions();
        $this->seedRoles();
        $this->syncExistingUserRoles();
    }

    private function seedPermissions(): void
    {
        foreach (Permissions::all() as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
    }

    private function seedRoles(): void
    {
        $legacy = Role::where('name', 'SuperAdmin')->first();
        if ($legacy && $legacy->name !== Permissions::SUPERADMIN_ROLE) {
            $legacy->name = Permissions::SUPERADMIN_ROLE;
            $legacy->save();
        }

        $superAdminRole = Role::firstOrCreate([
            'name' => Permissions::SUPERADMIN_ROLE,
            'guard_name' => 'web',
        ]);
        $superAdminRole->syncPermissions(Permission::all());

        Role::firstOrCreate([
            'name' => Permissions::USER_ROLE,
            'guard_name' => 'web',
        ]);
    }

    private function syncExistingUserRoles(): void
    {
        User::query()
            ->whereNotNull('role_id')
            ->with('role')
            ->get()
            ->each(function (User $user) {
                if ($user->role) {
                    $user->syncRoles([$user->role]);
                    $user->forgetCachedPermissions();
                }
            });
    }
}
