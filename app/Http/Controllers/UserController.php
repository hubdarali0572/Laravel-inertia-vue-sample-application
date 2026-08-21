<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\Permissions;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:'.Permissions::VIEW_USER, only: ['index', 'show']),
            new Middleware('permission:'.Permissions::CREATE_USER, only: ['create', 'store']),
            new Middleware('permission:'.Permissions::EDIT_USER, only: ['edit', 'update']),
            new Middleware('permission:'.Permissions::DELETE_USER, only: ['destroy']),
        ];
    }

    public function index()
    {
        $this->authorize(Permissions::VIEW_USER);

        $users = User::whereHas('role', function ($query) {
            $query->whereRaw('LOWER(name) != ?', [Permissions::SUPERADMIN_ROLE]);
        })
            ->with(['role', 'media'])
            ->latest()
            ->paginate(10)
            ->through(fn ($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'profile_image' => $user->profile_image_url,
            ]);

        return Inertia::render('Users/Index', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        $this->authorize(Permissions::CREATE_USER);

        return Inertia::render('Users/Create', [
            'roles' => $this->assignableRoles(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize(Permissions::CREATE_USER);

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'role_id' => 'required|exists:roles,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $role = $this->resolveAssignableRole($request->role_id);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->user_type = 'user';
        $user->role_id = $role->id;
        $user->save();
        $user->syncAssignedRole($role);

        if ($request->hasFile('image')) {
            $user->addMediaFromRequest('image')->toMediaCollection('images');
        }

        return redirect()->route('users.index')->with('success', __('ui.flash.user_created'));
    }

    public function show(string $id)
    {
        $this->authorize(Permissions::SHOW_USER);

        $user = User::with(['role', 'media'])->findOrFail($id);

        abort_if($user->isSuperAdmin(), 403);

        return Inertia::render('Users/Edit', [
            'user' => $user,
            'roles' => $this->assignableRoles(),
            'user_image' => $user->profile_image_url,
        ]);
    }

    public function edit(string $id)
    {
        $this->authorize(Permissions::EDIT_USER);

        $user = User::with(['role', 'media'])->findOrFail($id);

        abort_if($user->isSuperAdmin(), 403);

        return Inertia::render('Users/Edit', [
            'user' => $user,
            'roles' => $this->assignableRoles(),
            'user_image' => $user->profile_image_url,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $this->authorize(Permissions::EDIT_USER);

        $user = User::findOrFail($id);

        abort_if($user->isSuperAdmin(), 403);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|confirmed|min:6',
            'role_id' => 'required|exists:roles,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $role = $this->resolveAssignableRole($request->role_id);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        $user->syncAssignedRole($role);

        if ($request->hasFile('image')) {
            $user->clearMediaCollection('images');
            $user->addMediaFromRequest('image')->toMediaCollection('images');
        }

        return redirect()
            ->route('users.index')
            ->with('success', __('ui.flash.user_updated'));
    }

    public function destroy(string $id)
    {
        $this->authorize(Permissions::DELETE_USER);

        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('danger', __('ui.flash.cannot_delete_self'));
        }

        if ($user->isSuperAdmin()) {
            return back()->with('danger', __('ui.flash.cannot_delete_superadmin'));
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('danger', __('ui.flash.user_deleted'));
    }

    private function assignableRoles()
    {
        return Role::whereRaw('LOWER(name) != ?', [Permissions::SUPERADMIN_ROLE])
            ->orderBy('name')
            ->get();
    }

    private function resolveAssignableRole(int $roleId): Role
    {
        $role = Role::findOrFail($roleId);

        abort_if(Permissions::isSuperAdminRole($role->name), 403);

        return $role;
    }
}
