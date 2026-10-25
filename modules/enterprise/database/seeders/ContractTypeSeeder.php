<?php

declare(strict_types=1);

namespace Modules\Enterprise\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Enterprise\Models\Company;
use Modules\Enterprise\Models\ContractType;

class ContractTypeSeeder extends Seeder
{
    public function run(): void
    {
        Company::query()->each(function (Company $company): void {
            foreach ([
                'PERMANENT' => 'Permanent',
                'FIXED_TERM' => 'Fixed term',
                'CONTRACTOR' => 'Contractor',
            ] as $code => $name) {
                ContractType::query()->updateOrCreate(
                    ['company_id' => $company->getKey(), 'code' => $code],
                    ['name' => $name],
                );
            }
        });
    }
}
