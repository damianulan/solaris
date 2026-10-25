<?php

declare(strict_types=1);

namespace Modules\Enterprise\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Enterprise\Models\Company;
use Modules\Enterprise\Models\ContractType;
use Modules\Enterprise\Models\Employment;

/**
 * @extends Factory<Employment>
 */
class EmploymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'company_id' => Company::factory(),
            'department_id' => null,
            'contract_type_id' => fn (array $attributes) => ContractType::factory()->createOne([
                'company_id' => $attributes['company_id'],
            ])->getKey(),
            'employee_number' => fake()->optional()->bothify('EMP-#####'),
            'position_title' => fake()->jobTitle(),
            'started_on' => fake()->dateTimeBetween('-5 years', '-1 month'),
            'ended_on' => null,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (): array => [
            'started_on' => fake()->dateTimeBetween('-5 years', 'now'),
            'ended_on' => null,
        ]);
    }

    public function outdated(): static
    {
        return $this->state(fn (): array => [
            'started_on' => fake()->dateTimeBetween('-8 years', '-3 years'),
            'ended_on' => fake()->dateTimeBetween('-2 years', '-1 day'),
        ]);
    }
}
