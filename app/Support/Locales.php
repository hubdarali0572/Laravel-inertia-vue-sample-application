<?php

namespace App\Support;

class Locales
{
    public static function default(): string
    {
        return (string) config('locales.default', 'en');
    }

    /**
     * @return list<string>
     */
    public static function codes(): array
    {
        return collect(self::enabled())
            ->pluck('code')
            ->values()
            ->all();
    }

    public static function isSupported(?string $locale): bool
    {
        return is_string($locale) && in_array($locale, self::codes(), true);
    }

    /**
     * @return array<string, array{code: string, name: string, short: string, dir: string, enabled: bool}>
     */
    public static function enabled(): array
    {
        return collect(config('locales.supported', []))
            ->filter(fn ($locale) => is_array($locale) && ($locale['enabled'] ?? false))
            ->all();
    }

    /**
     * Compact list shared with Inertia for the language switcher.
     *
     * @return list<array{code: string, name: string, short: string, dir: string}>
     */
    public static function options(): array
    {
        return collect(self::enabled())
            ->map(fn ($locale) => [
                'code' => $locale['code'],
                'name' => $locale['name'],
                'short' => $locale['short'],
                'dir' => $locale['dir'],
            ])
            ->values()
            ->all();
    }

    public static function direction(?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $supported = config('locales.supported.'.$locale);

        return is_array($supported) ? ($supported['dir'] ?? 'ltr') : 'ltr';
    }

    public static function sanitize(?string $locale): string
    {
        return self::isSupported($locale) ? $locale : self::default();
    }
}
