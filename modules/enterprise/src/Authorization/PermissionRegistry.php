<?php

declare(strict_types=1);

namespace Modules\Enterprise\Authorization;

use Sentinel\Contracts\PermissionRegistryContract;

enum PermissionRegistry: string implements PermissionRegistryContract
{
    public static function permissions(): array
    {
        return [

        ];
    }
}
