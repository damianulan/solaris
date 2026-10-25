<?php

declare(strict_types=1);

use App\Authorization\PermissionRegistry as ApplicationPermissionRegistry;
use App\Models\User;
use App\Policies\UserPolicy;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Gate;
use Modules\Enterprise\Authorization\RoleRegistry;
use Modules\Enterprise\Models\Company;
use Modules\Enterprise\Models\ContractType;
use Modules\Enterprise\Models\Department;
use Modules\Enterprise\Models\Employment;
use Modules\Enterprise\Models\ManagerAssignment;
use Tests\TestCase;

uses(TestCase::class, LazilyRefreshDatabase::class);

function syncEnterpriseRoles(): void
{
    Artisan::call('sentinel:run');
}

it('supports department trees and multiple current and historical employments', function (): void {
    syncEnterpriseRoles();

    $company = Company::factory()->createOne();
    $parentDepartment = Department::factory()->for($company)->createOne();
    $childDepartment = Department::factory()->childOf($parentDepartment)->createOne();
    $contractType = ContractType::factory()->for($company)->createOne();
    $employee = User::factory()->createOne();

    Employment::factory()->count(2)->active()->create([
        'user_id' => $employee->getKey(),
        'company_id' => $company->getKey(),
        'department_id' => $childDepartment->getKey(),
        'contract_type_id' => $contractType->getKey(),
    ]);
    Employment::factory()->outdated()->create([
        'user_id' => $employee->getKey(),
        'company_id' => $company->getKey(),
        'department_id' => $parentDepartment->getKey(),
        'contract_type_id' => $contractType->getKey(),
    ]);

    expect($parentDepartment->children()->first()?->is($childDepartment))->toBeTrue()
        ->and($childDepartment->parent()->first()?->is($parentDepartment))->toBeTrue()
        ->and($employee->employments()->count())->toBe(3)
        ->and($employee->activeEmployments()->count())->toBe(2)
        ->and($employee->isEmployee())->toBeTrue()
        ->and($employee->isEmployedBy($company))->toBeTrue();
});

it('grants HR global user and employment edit privileges', function (): void {
    syncEnterpriseRoles();

    $hrUser = User::factory()->createOne();
    $employee = User::factory()->createOne();

    $hrUser->assignRole(RoleRegistry::Hr);

    expect($hrUser->can('view', $employee))->toBeTrue()
        ->and($hrUser->can('update', $employee))->toBeTrue()
        ->and($hrUser->hasPermissionTo(ApplicationPermissionRegistry::UpdateUsers))->toBeTrue();
});

it('limits manager privileges to users with active manager assignments', function (): void {
    syncEnterpriseRoles();

    $company = Company::factory()->createOne();
    $manager = User::factory()->createOne();
    $employee = User::factory()->createOne();
    $otherEmployee = User::factory()->createOne();
    $assignment = ManagerAssignment::factory()->active()->createOne([
        'company_id' => $company->getKey(),
        'manager_user_id' => $manager->getKey(),
        'employee_user_id' => $employee->getKey(),
    ]);

    $manager->assignRole(RoleRegistry::Manager, $employee);

    expect($manager->manages($employee))->toBeTrue()
        ->and($manager->manages($otherEmployee))->toBeFalse()
        ->and(Gate::getPolicyFor($employee))->toBeInstanceOf(UserPolicy::class)
        ->and($manager->canManage($employee, ApplicationPermissionRegistry::UpdateUsers))->toBeTrue()
        ->and($manager->can('update', $employee))->toBeTrue()
        ->and($manager->can('update', $otherEmployee))->toBeFalse();

    $assignment->update(['ended_on' => now()->subDay()]);

    expect($manager->manages($employee))->toBeFalse()
        ->and($manager->can('update', $employee))->toBeFalse();
});

it('registers employee roles for users with employment records', function (): void {
    syncEnterpriseRoles();

    $company = Company::factory()->createOne();
    $contractType = ContractType::factory()->for($company)->createOne();
    $employee = User::factory()->createOne();

    Employment::factory()->active()->create([
        'user_id' => $employee->getKey(),
        'company_id' => $company->getKey(),
        'contract_type_id' => $contractType->getKey(),
    ]);
    $employee->assignRole(RoleRegistry::Employee);

    expect($employee->isEmployee())->toBeTrue()
        ->and($employee->hasRole(RoleRegistry::Employee))->toBeTrue();
});

it('seeds a reusable company structure and its role assignments', function (): void {
    app(DatabaseSeeder::class)->run();

    $employees = User::all()->filter(
        fn (User $user): bool => $user->hasRole(RoleRegistry::Employee),
    );
    $hrUsers = User::all()->filter(
        fn (User $user): bool => $user->hasRole(RoleRegistry::Hr),
    );

    expect(Company::query()->count())->toBe(3)
        ->and(Department::query()->count())->toBe(12)
        ->and(ContractType::query()->count())->toBe(9)
        ->and(Employment::query()->count())->toBe(36)
        ->and(ManagerAssignment::query()->count())->toBe(12)
        ->and($employees->count())->toBe(30)
        ->and($hrUsers->count())->toBe(1);
});
