<?php

namespace App\Http\Middleware;

use App\Support\Locales;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()?->getName();

        if (is_string($routeName) && str_starts_with($routeName, 'publicSite.')) {
            app()->setLocale('en');
            Carbon::setLocale('en');

            return $next($request);
        }

        $locale = Locales::sanitize(
            $request->session()->get('locale')
            ?? $request->cookie(config('locales.cookie'))
        );

        app()->setLocale($locale);
        Carbon::setLocale($locale);

        $request->session()->put('locale', $locale);

        return $next($request);
    }
}
