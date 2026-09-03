<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        if ($this->shouldForceHttps()) {
            URL::forceScheme('https');
        }

        Relation::enforceMorphMap([
            'user' => User::class,
            'role' => \App\Models\Role::class,
            'permission' => \App\Models\Permission::class,
        ]);

        Gate::before(function ($user, ?string $ability = null) {
            return $user instanceof User && $user->isSuperAdmin() ? true : null;
        });
    }

    private function shouldForceHttps(): bool
    {
        if (filter_var(config('app.force_https'), FILTER_VALIDATE_BOOL)) {
            return true;
        }

        return str_starts_with((string) config('app.url'), 'https://');
    }
}
