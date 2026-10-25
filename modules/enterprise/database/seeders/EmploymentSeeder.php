<?php

declare(strict_types=1);

namespace Modules\Enterprise\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Enterprise\Authorization\RoleRegistry;
use Modules\Enterprise\Models\Company;
use Modules\Enterprise\Models\Employment;

class EmploymentSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::query()->orderBy('id')->get();

        if ($companies->isEmpty()) {
            return;
        }

        User::all()->sortBy('id')->values()->each(function (User $user, int $index) use ($companies): void {
            /** @var Company $company */
            $company = $companies[$index % $companies->count()];
            $department = $company->departments()->oldest('id')->firstOrFail();
            $contractType = $company->contractTypes()->oldest('id')->firstOrFail();

            Employment::factory()->active()->create([
                'user_id' => $user->getKey(),
                'company_id' => $company->getKey(),
                'department_id' => $department->getKey(),
                'contract_type_id' => $contractType->getKey(),
            ]);

            if (0 === $index % 5) {
                Employment::factory()->outdated()->create([
                    'user_id' => $user->getKey(),
                    'company_id' => $company->getKey(),
                    'department_id' => $department->getKey(),
                    'contract_type_id' => $contractType->getKey(),
                ]);
            }

            $user->assignRole(RoleRegistry::Employee);
        });
    }
}
