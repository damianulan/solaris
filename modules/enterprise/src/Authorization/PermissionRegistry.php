<?php

declare(strict_types=1);

namespace Modules\Enterprise\Authorization;

use Sentinel\Contracts\PermissionRegistryContract;

enum PermissionRegistry: string implements PermissionRegistryContract
{
    case ViewCompanies = 'enterprise.companies.view';
    case UpdateCompanies = 'enterprise.companies.update';
    case ViewDepartments = 'enterprise.departments.view';
    case UpdateDepartments = 'enterprise.departments.update';
    case ViewContractTypes = 'enterprise.contract-types.view';
    case UpdateContractTypes = 'enterprise.contract-types.update';
    case ViewEmployments = 'enterprise.employments.view';
    case CreateEmployments = 'enterprise.employments.create';
    case UpdateEmployments = 'enterprise.employments.update';
    case DeleteEmployments = 'enterprise.employments.delete';
    case ViewManagerAssignments = 'enterprise.manager-assignments.view';
    case CreateManagerAssignments = 'enterprise.manager-assignments.create';
    case UpdateManagerAssignments = 'enterprise.manager-assignments.update';
    case DeleteManagerAssignments = 'enterprise.manager-assignments.delete';

    public static function permissions(): array
    {
        return [
            self::ViewCompanies->value => true,
            self::UpdateCompanies->value => true,
            self::ViewDepartments->value => true,
            self::UpdateDepartments->value => true,
            self::ViewContractTypes->value => true,
            self::UpdateContractTypes->value => true,
            self::ViewEmployments->value => true,
            self::CreateEmployments->value => true,
            self::UpdateEmployments->value => true,
            self::DeleteEmployments->value => true,
            self::ViewManagerAssignments->value => true,
            self::CreateManagerAssignments->value => true,
            self::UpdateManagerAssignments->value => true,
            self::DeleteManagerAssignments->value => true,
        ];
    }
}
