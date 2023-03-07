<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ShopFactory extends Factory
{
    public function definition(): array
    {
        return [
            'company_name' => fake()->company(),
            'description' => fake()->text(),
            'company_code' => fake()->companySuffix(),
            'company_address' => fake()->address(),
            'company_phone' => fake()->phoneNumber(),
        ];
    }
}
