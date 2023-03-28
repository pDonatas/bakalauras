<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->jobTitle(),
            'description' => fake()->text(),
            'price' => fake()->randomFloat(2, 0, 1000),
            'category_id' => $this->faker->numberBetween(1, 10),
        ];
    }
}
