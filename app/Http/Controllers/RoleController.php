<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\Permissions;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
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

        $roles = Role::whereRaw('LOWER(name) != ?', [Permissions::SUPERADMIN_ROLE])
            ->latest()
            ->paginate(10);

        return Inertia::render('Roles/Index', [
            'roles' => $roles,
        ]);
    }

    public function create()
    {
        $this->authorize(Permissions::CREATE_ROLE);

        return Inertia::render('Roles/Create', [
            'permissionGroups' => $this->permissionGroups(),
        ]);
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

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($this->assignablePermissionIds($request->permissions));
        app(PermissionRegistrar::class)->forgetCachedPermissions();

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

        $role = Role::findOrFail($id);

        abort_if(Permissions::isSuperAdminRole($role->name), 403);

        return Inertia::render('Roles/Edit', [
            'role' => $role,
            'permissionGroups' => $this->permissionGroups(),
            'rolePermissions' => $role->permissions
                ->whereIn('name', Permissions::assignable())
                ->pluck('id')
                ->values()
                ->all(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $this->authorize(Permissions::EDIT_ROLE);

        $role = Role::findOrFail($id);

        abort_if(Permissions::isSuperAdminRole($role->name), 403);

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,'.$role->id,
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'exists:permissions,id',
        ]);

        abort_if(Permissions::isSuperAdminRole($request->name), 403);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($this->assignablePermissionIds($request->permissions));
        app(PermissionRegistrar::class)->forgetCachedPermissions();

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

        $role->delete();
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()
            ->route('roles.index')
            ->with('danger', __('ui.flash.role_deleted'));
    }

    private function permissionGroups(): array
    {
        $allPermissions = Permission::all()->keyBy('name');
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
