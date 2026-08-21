<?php

namespace App\Http\Middleware;

use App\Support\Permissions;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Middleware;
use Spatie\Permission\PermissionRegistrar;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        if ($user) {
            // Load roles/permissions from the authenticated user's current
            // database connection so multi-school/tenant setups stay in sync.
            $user->loadMissing(['role']);
            app(PermissionRegistrar::class)->setPermissionsTeamId(null);
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'user_type' => $user->user_type,
                    'role_name' => $user->role?->name
                        ?? $user->getRoleNames()->first(),
                    'email_verified_at' => $user->email_verified_at,
                ] : null,
                'permissions' => $user
                    ? ($user->isSuperAdmin()
                        ? Permissions::all()
                        : $user->getAllPermissions()
                            ->pluck('name')
                            ->reject(fn ($name) => str_contains($name, 'activity'))
                            ->values()
                            ->all())
                    : [],
                'roles' => $user ? $user->getRoleNames()->values()->all() : [],
                'is_superadmin' => $user?->isSuperAdmin() ?? false,
            ],
            'canLogin' => fn () => Route::has('login'),
            'canRegister' => fn () => Route::has('register'),
            'laravelVersion' => fn () => Application::VERSION,
            'phpVersion' => fn () => PHP_VERSION,
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'danger' => fn () => $request->session()->get('danger'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'recaptcha_site_key' => env('GOOGLE_RECAPTCHA_KEY'),
        ];
    }
}
