<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 10),
            'shop_id' => $this->faker->numberBetween(1, 10),
            'total_price' => $this->faker->numberBetween(10000, 100000),
            'status' => $this->faker->randomElement(['pending', 'processing', 'completed', 'cancelled']),
            'payment_method' => $this->faker->randomElement(['cod', 'paysera']),
            'payment_status' => $this->faker->randomElement(['paid', 'unpaid']),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'notes' => $this->faker->text(),
        ];
    }
}
