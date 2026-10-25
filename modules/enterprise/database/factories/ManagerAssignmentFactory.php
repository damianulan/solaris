<?php

declare(strict_types=1);

namespace Modules\Enterprise\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Enterprise\Models\Company;
use Modules\Enterprise\Models\ManagerAssignment;

/**
 * @extends Factory<ManagerAssignment>
 */
class ManagerAssignmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'manager_user_id' => User::factory(),
            'employee_user_id' => User::factory(),
            'started_on' => fake()->dateTimeBetween('-3 years', 'now'),
            'ended_on' => null,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (): array => [
            'started_on' => fake()->dateTimeBetween('-3 years', 'now'),
            'ended_on' => null,
        ]);
    }

    public function outdated(): static
    {
        return $this->state(fn (): array => [
            'started_on' => fake()->dateTimeBetween('-6 years', '-2 years'),
            'ended_on' => fake()->dateTimeBetween('-1 year', '-1 day'),
        ]);
    }
}
