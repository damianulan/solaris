<?php

declare(strict_types=1);

namespace Modules\Enterprise\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Enterprise\Models\Company;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => fake()->unique()->bothify('COMP-####'),
            'name' => fake()->company(),
            'legal_name' => fake()->optional()->company(),
            'registration_number' => fake()->optional()->numerify('##########'),
            'tax_identifier' => fake()->optional()->numerify('##########'),
            'email' => fake()->optional()->companyEmail(),
            'phone' => fake()->optional()->phoneNumber(),
            'website' => fake()->optional()->url(),
        ];
    }
}
