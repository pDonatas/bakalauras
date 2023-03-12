<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->jobTitle(),
            'description' => fake()->text(),
            'price' => fake()->randomFloat(2, 0, 1000),
            'worker_id' => $this->faker->numberBetween(1, 10),
        ];
    }
}
