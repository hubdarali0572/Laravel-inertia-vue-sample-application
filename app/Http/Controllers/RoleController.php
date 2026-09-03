<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Support\ActivityLogger;
use App\Support\Permissions;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:'.Permissions::VIEW_ROLE, only: ['index', 'show']),
            new Middleware('permission:'.Permissions::CREATE_ROLE, only: ['create', 'store']),
            new Middleware('permission:'.Permissions::EDIT_ROLE, only: ['edit', 'update']),
            new Middleware('permission:'.Permissions::DELETE_ROLE, only: ['destroy']),
        ];
    }

    public function index()
    {
        $this->authorize(Permissions::VIEW_ROLE);

        return Inertia::render('Roles/Index', $this->indexProps());
    }

    public function create()
    {
        $this->authorize(Permissions::CREATE_ROLE);

        return $this->index();
    }

    public function store(Request $request)
    {
        $this->authorize(Permissions::CREATE_ROLE);

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'exists:permissions,id',
        ], [
            'permissions.required' => __('validation.custom.permissions.required'),
            'name.unique' => __('validation.custom.name.unique'),
        ]);

        abort_if(Permissions::isSuperAdminRole($request->name), 403);

        $permissionIds = $this->assignablePermissionIds($request->permissions);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($permissionIds);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        ActivityLogger::attachPermissionsToRoleLog(
            $role,
            $role->permissions()->pluck('name')->all(),
            'created',
        );

        return redirect()
            ->route('roles.index')
            ->with('success', __('ui.flash.role_created', ['name' => strtoupper($role->name)]));
    }

    public function show(string $id)
    {
        $this->authorize(Permissions::SHOW_ROLE);

        return $this->edit($id);
    }

    public function edit(string $id)
    {
        $this->authorize(Permissions::EDIT_ROLE);
        $this->authorize(Permissions::VIEW_ROLE);

        $role = Role::with('permissions')->findOrFail($id);

        abort_if(Permissions::isSuperAdminRole($role->name), 403);

        return Inertia::render('Roles/Index', array_merge($this->indexProps(), [
            'editingRole' => $this->formatRoleForForm($role),
        ]));
    }

    public function update(Request $request, string $id)
    {
        $this->authorize(Permissions::EDIT_ROLE);

        $role = Role::with('permissions')->findOrFail($id);

        abort_if(Permissions::isSuperAdminRole($role->name), 403);

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,'.$role->id,
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'exists:permissions,id',
        ]);

        abort_if(Permissions::isSuperAdminRole($request->name), 403);

        $oldPermissions = $role->permissions
            ->whereIn('name', Permissions::assignable())
            ->pluck('name')
            ->all();

        $role->update(['name' => $request->name]);

        $permissionIds = $this->assignablePermissionIds($request->permissions);
        $role->syncPermissions($permissionIds);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $role->load('permissions');

        ActivityLogger::logRolePermissionChanges(
            $role,
            $oldPermissions,
            $role->permissions
                ->whereIn('name', Permissions::assignable())
                ->pluck('name')
                ->all(),
        );

        return redirect()
            ->route('roles.index')
            ->with('success', __('ui.flash.role_updated'));
    }

    public function destroy(string $id)
    {
        $this->authorize(Permissions::DELETE_ROLE);

        $role = Role::findOrFail($id);

        if (Permissions::isSuperAdminRole($role->name)) {
            return back()->with('danger', __('ui.flash.cannot_delete_superadmin_role'));
        }

        $isAssigned = $role->users()->exists()
            || User::where('role_id', $role->id)->exists();

        if ($isAssigned) {
            return back()->with('danger', __('ui.flash.role_assigned'));
        }

        $permissionNames = $role->permissions()->pluck('name')->all();

        $role->delete();
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        ActivityLogger::attachPermissionsToRoleLog($role, $permissionNames, 'deleted');

        return redirect()
            ->route('roles.index')
            ->with('danger', __('ui.flash.role_deleted'));
    }

    private function indexProps(): array
    {
        $roles = Role::with('permissions')
            ->whereRaw('LOWER(name) != ?', [Permissions::SUPERADMIN_ROLE])
            ->latest()
            ->paginate(10)
            ->through(fn ($role) => $this->formatRoleForForm($role));

        $props = [
            'roles' => $roles,
            'permissionGroups' => [],
            'editingRole' => null,
        ];

        if (
            auth()->user()?->can(Permissions::CREATE_ROLE)
            || auth()->user()?->can(Permissions::EDIT_ROLE)
        ) {
            $props['permissionGroups'] = $this->permissionGroups();
        }

        return $props;
    }

    private function formatRoleForForm(Role $role): array
    {
        return [
            'id' => $role->id,
            'name' => $role->name,
            'permission_ids' => $role->permissions
                ->whereIn('name', Permissions::assignable())
                ->pluck('id')
                ->values()
                ->all(),
        ];
    }

    private function permissionGroups(): array
    {
        $allPermissions = Permission::query()->get()->keyBy('name');
        $groups = [];

        foreach (Permissions::groups() as $groupName => $names) {
            $groups[$groupName] = collect($names)
                ->map(fn ($name) => $allPermissions->get($name))
                ->filter()
                ->values();
        }

        return $groups;
    }

    private function assignablePermissionIds(array $permissionIds): array
    {
        return Permission::query()
            ->whereIn('id', $permissionIds)
            ->whereIn('name', Permissions::assignable())
            ->pluck('id')
            ->all();
    }
}
