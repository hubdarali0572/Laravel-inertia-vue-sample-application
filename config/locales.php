<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default application language
    |--------------------------------------------------------------------------
    |
    | English is the default locale. The SetLocale middleware falls back to
    | this value when the visitor has not chosen a language yet.
    |
    */
    'default' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Supported languages
    |--------------------------------------------------------------------------
    |
    | Add future languages in this array only. Do not scatter locale lists
    | across Vue pages or controllers.
    |
    | Each entry:
    |   code      - ISO 639-1 code (also the lang/{code}/ folder name)
    |   name      - native display name
    |   short     - compact header label
    |   dir       - ltr or rtl
    |   enabled   - set false to hide from the switcher without deleting it
    |
    | Example for a future language (do not enable until translations exist):
    |
    |   'ar' => [
    |       'code' => 'ar',
    |       'name' => 'العربية',
    |       'short' => 'AR',
    |       'dir' => 'rtl',
    |       'enabled' => true,
    |   ],
    |
    | Then add matching files: lang/ar/ui.php, lang/ar/pagination.php,
    | lang/ar/validation.php, lang/ar/auth.php, lang/ar/passwords.php
    |
    */
    'supported' => [

        'en' => [
            'code' => 'en',
            'name' => 'English',
            'short' => 'EN',
            'dir' => 'ltr',
            'enabled' => true,
        ],

        'ur' => [
            'code' => 'ur',
            'name' => 'اردو',
            'short' => 'UR',
            'dir' => 'rtl',
            'enabled' => true,
        ],

        // Add the next language here. Keep English and Urdu as the only
        // enabled locales until translations are ready.

    ],

    'cookie' => 'app_locale',

    'cookie_minutes' => 60 * 24 * 365,
];
