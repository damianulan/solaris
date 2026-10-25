<?php

declare(strict_types=1);

namespace Modules\Enterprise\Authorization;

use Sentinel\Contracts\RoleRegistryContract;

enum RoleRegistry: string implements RoleRegistryContract
{
    public static function roles(): array
    {
        return [

        ];
    }

    public static function rolePermissions(): array
    {
        return [

        ];
    }

    public static function adminRoles(): array
    {
        return [

        ];
    }
}
