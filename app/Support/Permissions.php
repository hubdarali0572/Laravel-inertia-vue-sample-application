<?php

namespace App\Support;

class Permissions
{
    public const SUPERADMIN_ROLE = 'superadmin';

    public const USER_ROLE = 'user';

    public const VIEW_USER = 'view user';

    public const CREATE_USER = 'create user';

    public const EDIT_USER = 'edit user';

    public const SHOW_USER = 'show user';

    public const DELETE_USER = 'delete user';

    public const VIEW_ROLE = 'view role';

    public const CREATE_ROLE = 'create role';

    public const EDIT_ROLE = 'edit role';

    public const SHOW_ROLE = 'show role';

    public const DELETE_ROLE = 'delete role';

    public const VIEW_ACTIVITY = 'view activity';

    public const DELETE_ACTIVITY = 'delete activity';

    /**
     * Canonical permission names used across routes, controllers, and the UI.
     *
     * @return list<string>
     */
    public static function all(): array
    {
        return [
            self::VIEW_USER,
            self::CREATE_USER,
            self::EDIT_USER,
            self::SHOW_USER,
            self::DELETE_USER,
            self::VIEW_ROLE,
            self::CREATE_ROLE,
            self::EDIT_ROLE,
            self::SHOW_ROLE,
            self::DELETE_ROLE,
            self::VIEW_ACTIVITY,
            self::DELETE_ACTIVITY,
        ];
    }

    /**
     * Permission groups shown on role create/edit screens.
     *
     * @return array<string, list<string>>
     */
    public static function groups(): array
    {
        return [
            'User Management' => [
                self::VIEW_USER,
                self::CREATE_USER,
                self::SHOW_USER,
                self::EDIT_USER,
                self::DELETE_USER,
            ],
            'Role Management' => [
                self::VIEW_ROLE,
                self::CREATE_ROLE,
                self::SHOW_ROLE,
                self::EDIT_ROLE,
                self::DELETE_ROLE,
            ],
        ];
    }

    /**
     * Permission names that can be assigned to regular roles.
     * Activity logs remain superadmin-only and are never assignable.
     *
     * @return list<string>
     */
    public static function assignable(): array
    {
        return collect(self::groups())->flatten()->values()->all();
    }

    public static function isSuperAdminRole(?string $name): bool
    {
        return is_string($name) && strtolower($name) === self::SUPERADMIN_ROLE;
    }
}
