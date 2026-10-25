<?php

declare(strict_types=1);

namespace Modules\Enterprise\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Enterprise\Models\Company;
use Modules\Enterprise\Models\ContractType;

/**
 * @extends Factory<ContractType>
 */
class ContractTypeFactory extends Factory
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
            'code' => fake()->unique()->bothify('CONTRACT-####'),
            'name' => fake()->unique()->words(2, true),
            'description' => fake()->optional()->sentence(),
        ];
    }
}
