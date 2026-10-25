<?php

declare(strict_types=1);

namespace Modules\Enterprise\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Enterprise\Models\Company;
use Modules\Enterprise\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        Company::query()->each(function (Company $company): void {
            $headOffice = Department::query()->updateOrCreate(
                ['company_id' => $company->getKey(), 'code' => 'HEAD'],
                ['name' => 'Head Office', 'parent_id' => null],
            );

            foreach ([
                'HR' => 'Human Resources',
                'FIN' => 'Finance',
                'OPS' => 'Operations',
            ] as $code => $name) {
                Department::query()->updateOrCreate(
                    ['company_id' => $company->getKey(), 'code' => $code],
                    ['name' => $name, 'parent_id' => $headOffice->getKey()],
                );
            }
        });
    }
}
