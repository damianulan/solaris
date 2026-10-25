<?php

declare(strict_types=1);

namespace Modules\Enterprise\Authorization;

use App\Authorization\PermissionRegistry as ApplicationPermissionRegistry;
use Sentinel\Contracts\RoleRegistryContract;

enum RoleRegistry: string implements RoleRegistryContract
{
    case Employee = 'employee';
    case Hr = 'hr';
    case Manager = 'manager';

    public static function roles(): array
    {
        return [
            self::Employee->value => true,
            self::Hr->value => true,
            self::Manager->value => true,
        ];
    }

    public static function rolePermissions(): array
    {
        return [
            self::Hr->value => [
                ApplicationPermissionRegistry::ViewUsers->value,
                ApplicationPermissionRegistry::UpdateUsers->value,
                PermissionRegistry::ViewCompanies->value,
                PermissionRegistry::UpdateCompanies->value,
                PermissionRegistry::ViewDepartments->value,
                PermissionRegistry::UpdateDepartments->value,
                PermissionRegistry::ViewContractTypes->value,
                PermissionRegistry::UpdateContractTypes->value,
                PermissionRegistry::ViewEmployments->value,
                PermissionRegistry::CreateEmployments->value,
                PermissionRegistry::UpdateEmployments->value,
                PermissionRegistry::DeleteEmployments->value,
                PermissionRegistry::ViewManagerAssignments->value,
                PermissionRegistry::CreateManagerAssignments->value,
                PermissionRegistry::UpdateManagerAssignments->value,
                PermissionRegistry::DeleteManagerAssignments->value,
            ],
            self::Manager->value => [
                ApplicationPermissionRegistry::ViewUsers->value,
                ApplicationPermissionRegistry::UpdateUsers->value,
                PermissionRegistry::ViewEmployments->value,
            ],
        ];
    }

    public static function adminRoles(): array
    {
        return [

        ];
    }
}
