<?php

declare(strict_types=1);

namespace Modules\Enterprise\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Enterprise\Models\Company;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        foreach (
            [
                ['code' => 'SOLARIS', 'name' => 'Solaris'],
                ['code' => 'NORTH', 'name' => 'North Company'],
                ['code' => 'SOUTH', 'name' => 'South Company'],
            ] as $company) {
            Company::query()->updateOrCreate(
                ['code' => $company['code']],
                ['name' => $company['name']],
            );
        }
    }
}
