<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Modules\Enterprise\Database\Seeders\EnterpriseDatabaseSeeder;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (0 !== Artisan::call('sentinel:run')) {
            throw new RuntimeException('Sentinel roles and permissions could not be synchronized.');
        }

        $this->call([
            UserSeeder::class,
            EnterpriseDatabaseSeeder::class,
        ]);
    }
}
