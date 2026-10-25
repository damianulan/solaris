<?php

declare(strict_types=1);

namespace App\Authorization;

use Sentinel\Contracts\PermissionRegistryContract;

enum PermissionRegistry: string implements PermissionRegistryContract
{
    case ViewUsers = 'users.view';
    case UpdateUsers = 'users.update';
    case DeleteUsers = 'users.delete';
    case SuspendUsers = 'users.suspend';

    public static function permissions(): array
    {
        return [
            self::ViewUsers->value => true,
            self::UpdateUsers->value => true,
            self::DeleteUsers->value => true,
            self::SuspendUsers->value => true,
        ];
    }
}
