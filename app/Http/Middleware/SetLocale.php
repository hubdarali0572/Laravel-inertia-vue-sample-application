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
