<?php

namespace App\Constants;

class UserRole
{
    const SUPER_ADMIN = 'super_admin';
    const EDITOR = 'editor';
    const REPORTER = 'reporter';

    /**
     * Get all available roles
     *
     * @return array
     */
    public static function all(): array
    {
        return [
            self::SUPER_ADMIN,
            self::EDITOR,
            self::REPORTER,
        ];
    }

    /**
     * Get role display name
     *
     * @param string $role
     * @return string
     */
    public static function getDisplayName(string $role): string
    {
        return match ($role) {
            self::SUPER_ADMIN => 'Super Admin',
            self::EDITOR => 'Editor',
            self::REPORTER => 'Reporter',
            default => 'Unknown Role',
        };
    }

    /**
     * Check if role exists
     *
     * @param string $role
     * @return bool
     */
    public static function exists(string $role): bool
    {
        return in_array($role, self::all());
    }

    /**
     * Get role hierarchy level (higher number = more permissions)
     *
     * @param string $role
     * @return int
     */
    public static function getHierarchyLevel(string $role): int
    {
        return match ($role) {
            self::SUPER_ADMIN => 3,
            self::EDITOR => 2,
            self::REPORTER => 1,
            default => 0,
        };
    }

    /**
     * Check if role has permission level
     *
     * @param string $role
     * @param int $requiredLevel
     * @return bool
     */
    public static function hasPermissionLevel(string $role, int $requiredLevel): bool
    {
        return self::getHierarchyLevel($role) >= $requiredLevel;
    }
}
