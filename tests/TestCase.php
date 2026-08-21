<?php

namespace Tests;

use App\Models\User;
use App\Support\Permissions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    protected function seedPermissions(): void
    {
        foreach (Permissions::all() as $name) {
            Permission::firstOrCreate([
                'name' => $name,
                'guard_name' => 'web',
            ]);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * @param  list<string>  $permissions
     */
    protected function createUserWithPermissions(array $permissions = [], array $attributes = []): User
    {
        $this->seedPermissions();

        $role = Role::create([
            'name' => 'role-'.uniqid(),
            'guard_name' => 'web',
        ]);

        if ($permissions !== []) {
            $role->syncPermissions($permissions);
        }

        $user = User::factory()->create($attributes);
        $user->syncAssignedRole($role);

        return $user->fresh();
    }

    protected function createSuperAdmin(array $attributes = []): User
    {
        $this->seedPermissions();

        $role = Role::firstOrCreate([
            'name' => Permissions::SUPERADMIN_ROLE,
            'guard_name' => 'web',
        ]);
        $role->syncPermissions(Permission::all());

        $user = User::factory()->create(array_merge([
            'user_type' => Permissions::SUPERADMIN_ROLE,
        ], $attributes));

        $user->syncAssignedRole($role);

        return $user->fresh();
    }
}
