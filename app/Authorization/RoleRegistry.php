<?php

declare(strict_types=1);

namespace App\Authorization;

use Sentinel\Contracts\RoleRegistryContract;

enum RoleRegistry: string implements RoleRegistryContract
{
    case Root = 'root';
    case Administrator = 'administrator';
    case User = 'user';

    public static function roles(): array
    {
        return [
            self::Root->value => false,
            self::Administrator->value => true,
            self::User->value => true,
        ];
    }

    public static function rolePermissions(): array
    {
        return [
            self::Root->value => ['*'],
            self::Administrator->value => [
                PermissionRegistry::ViewUsers->value,
                PermissionRegistry::UpdateUsers->value,
                PermissionRegistry::DeleteUsers->value,
                PermissionRegistry::SuspendUsers->value,
            ],
        ];
    }

    public static function adminRoles(): array
    {
        return [
            self::Root->value,
            self::Administrator->value,
        ];
    }
}
