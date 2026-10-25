<?php

declare(strict_types=1);

namespace Modules\Enterprise\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use RuntimeException;

class EnterpriseDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (0 !== Artisan::call('sentinel:run')) {
            throw new RuntimeException('Sentinel roles and permissions could not be synchronized.');
        }

        $this->call([
            CompanySeeder::class,
            DepartmentSeeder::class,
            ContractTypeSeeder::class,
            EmploymentSeeder::class,
            ManagerAssignmentSeeder::class,
        ]);
    }
}
