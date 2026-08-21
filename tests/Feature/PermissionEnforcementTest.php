<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\Permissions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class PermissionEnforcementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_protected_modules(): void
    {
        $this->get(route('users.index'))->assertRedirect(route('login'));
        $this->get(route('roles.index'))->assertRedirect(route('login'));
        $this->get(route('activity.index'))->assertRedirect(route('login'));
        $this->get(route('activity.show', 1))->assertRedirect(route('login'));
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_user_without_permissions_cannot_access_modules(): void
    {
        $user = $this->createUserWithPermissions([]);

        $this->actingAs($user)->get(route('users.index'))->assertForbidden();
        $this->actingAs($user)->get(route('users.create'))->assertForbidden();
        $this->actingAs($user)->get(route('roles.index'))->assertForbidden();
        $this->actingAs($user)->get(route('activity.index'))->assertForbidden();
        $this->actingAs($user)->get(route('dashboard'))->assertOk();
        $this->actingAs($user)->get(route('profile.edit'))->assertOk();
    }

    public function test_view_permission_does_not_allow_create_edit_or_delete(): void
    {
        $user = $this->createUserWithPermissions([Permissions::VIEW_USER]);
        $target = $this->createUserWithPermissions([Permissions::VIEW_ROLE]);

        $this->actingAs($user)->get(route('users.index'))->assertOk();
        $this->actingAs($user)->get(route('users.create'))->assertForbidden();
        $this->actingAs($user)->get(route('users.edit', $target))->assertForbidden();
        $this->actingAs($user)->delete(route('users.destroy', $target))->assertForbidden();
    }

    public function test_create_update_and_delete_permissions_are_enforced(): void
    {
        $creator = $this->createUserWithPermissions([
            Permissions::VIEW_USER,
            Permissions::CREATE_USER,
        ]);
        $editor = $this->createUserWithPermissions([
            Permissions::VIEW_USER,
            Permissions::EDIT_USER,
        ]);
        $deleter = $this->createUserWithPermissions([
            Permissions::VIEW_USER,
            Permissions::DELETE_USER,
        ]);
        $target = $this->createUserWithPermissions([Permissions::VIEW_ROLE]);

        $this->actingAs($creator)->get(route('users.create'))->assertOk();
        $this->actingAs($editor)->get(route('users.edit', $target))->assertOk();
        $this->actingAs($deleter)->delete(route('users.destroy', $target))->assertRedirect(route('users.index'));
        $this->assertNull($target->fresh());
    }

    public function test_creating_a_user_assigns_the_spatie_role(): void
    {
        $admin = $this->createSuperAdmin();
        $role = Role::create(['name' => 'editor', 'guard_name' => 'web']);
        $role->syncPermissions([Permissions::VIEW_USER]);

        $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'Assigned User',
            'email' => 'assigned@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role_id' => $role->id,
        ])->assertRedirect(route('users.index'));

        $created = User::where('email', 'assigned@example.com')->first();

        $this->assertNotNull($created);
        $this->assertSame($role->id, $created->role_id);
        $this->assertTrue($created->hasRole('editor'));
        $this->assertTrue($created->hasPermissionTo(Permissions::VIEW_USER));
    }

    public function test_updating_role_permissions_takes_effect_immediately(): void
    {
        $user = $this->createUserWithPermissions([Permissions::VIEW_USER]);
        $admin = $this->createSuperAdmin();

        $this->actingAs($user)->get(route('users.create'))->assertForbidden();

        $role = $user->roles()->first();
        $role->givePermissionTo(Permissions::CREATE_USER);
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $user->forgetCachedPermissions();

        $this->actingAs($user->fresh())->get(route('users.create'))->assertOk();

        $this->actingAs($admin)->put(route('roles.update', $role), [
            'name' => $role->name,
            'permissions' => $role->permissions()->pluck('id')->all(),
        ])->assertRedirect(route('roles.index'));
    }

    public function test_removing_a_permission_blocks_the_action(): void
    {
        $user = $this->createUserWithPermissions([
            Permissions::VIEW_USER,
            Permissions::CREATE_USER,
        ]);

        $this->actingAs($user)->get(route('users.create'))->assertOk();

        $user->roles()->first()->revokePermissionTo(Permissions::CREATE_USER);
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $user->forgetCachedPermissions();

        $this->actingAs($user->fresh())->get(route('users.create'))->assertForbidden();
    }

    public function test_superadmin_can_access_every_module(): void
    {
        $admin = $this->createSuperAdmin();

        $this->actingAs($admin)->get(route('users.index'))->assertOk();
        $this->actingAs($admin)->get(route('users.create'))->assertOk();
        $this->actingAs($admin)->get(route('roles.index'))->assertOk();
        $this->actingAs($admin)->get(route('roles.create'))->assertOk();
        $this->actingAs($admin)->get(route('activity.index'))->assertOk();
    }

    public function test_activity_logs_are_superadmin_only(): void
    {
        $user = $this->createUserWithPermissions([
            Permissions::VIEW_ACTIVITY,
            Permissions::DELETE_ACTIVITY,
        ]);
        $admin = $this->createSuperAdmin();

        $this->actingAs($user)->get(route('activity.index'))->assertForbidden();
        $this->actingAs($user)->delete(route('activity.clear'))->assertForbidden();
        $this->actingAs($admin)->get(route('activity.index'))->assertOk();
        $this->actingAs($admin)->delete(route('activity.clear'))->assertRedirect();
    }

    public function test_inertia_shares_the_authenticated_users_permissions(): void
    {
        $user = $this->createUserWithPermissions([Permissions::VIEW_USER]);

        $this->actingAs($user)
            ->get(route('users.index'))
            ->assertInertia(fn ($page) => $page
                ->where('auth.permissions', fn ($permissions) => collect($permissions)->contains(Permissions::VIEW_USER)
                    && ! collect($permissions)->contains(Permissions::CREATE_USER))
                ->where('auth.is_superadmin', false)
            );
    }
}
