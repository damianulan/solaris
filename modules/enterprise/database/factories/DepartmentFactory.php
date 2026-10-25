<?php

declare(strict_types=1);

namespace Modules\Enterprise\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Enterprise\Models\Company;
use Modules\Enterprise\Models\Department;

/**
 * @extends Factory<Department>
 */
class DepartmentFactory extends Factory
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
            'parent_id' => null,
            'code' => fake()->unique()->bothify('DEPT-####'),
            'name' => fake()->unique()->words(2, true),
            'description' => fake()->optional()->sentence(),
        ];
    }

    public function childOf(Department $parent): static
    {
        return $this->state(fn (): array => [
            'company_id' => $parent->getAttribute('company_id'),
            'parent_id' => $parent->getKey(),
        ]);
    }
}
