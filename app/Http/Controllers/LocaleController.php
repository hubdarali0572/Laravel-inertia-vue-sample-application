<?php

namespace App\Http\Controllers;

use App\Support\Locales;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'locale' => ['required', 'string', 'in:'.implode(',', Locales::codes())],
        ]);

        $locale = Locales::sanitize($validated['locale']);

        $request->session()->put('locale', $locale);
        app()->setLocale($locale);

        return back()->cookie(
            config('locales.cookie'),
            $locale,
            (int) config('locales.cookie_minutes'),
            '/',
            null,
            $request->isSecure(),
            false,
            false,
            'lax'
        );
    }
}
