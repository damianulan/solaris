<?php

declare(strict_types=1);

namespace Modules\Enterprise\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;
use Modules\Enterprise\Authorization\RoleRegistry;
use Modules\Enterprise\Models\Company;
use Modules\Enterprise\Models\Employment;
use Modules\Enterprise\Models\ManagerAssignment;

class ManagerAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        Company::query()->each(function (Company $company): void {
            $date = now()->toDateString();
            $employees = Employment::query()
                ->whereBelongsTo($company)
                ->where('started_on', '<=', $date)
                ->where(function (Builder $query) use ($date): void {
                    $query->whereNull('ended_on')->orWhere('ended_on', '>=', $date);
                })
                ->with('user')
                ->orderBy('id')
                ->get()
                ->pluck('user')
                ->filter(fn (mixed $user): bool => $user instanceof User)
                ->unique(fn (mixed $user): mixed => $user instanceof User ? $user->getKey() : null)
                ->values();

            $manager = $employees->first();

            if (! $manager instanceof User) {
                return;
            }

            foreach ($employees->skip(1)->take(4) as $employee) {
                if (! $employee instanceof User) {
                    continue;
                }

                ManagerAssignment::query()->firstOrCreate([
                    'company_id' => $company->getKey(),
                    'manager_user_id' => $manager->getKey(),
                    'employee_user_id' => $employee->getKey(),
                    'started_on' => now()->subYear()->toDateString(),
                    'ended_on' => null,
                ]);

                $manager->assignRole(RoleRegistry::Manager, $employee);
            }
        });

        User::all()->first(fn (User $user): bool => $user->employments()->exists())?->assignRole(RoleRegistry::Hr);
    }
}
