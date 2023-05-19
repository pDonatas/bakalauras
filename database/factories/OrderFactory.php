<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Order;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 10),
            'service_id' => Service::inRandomOrder()->first()->id,
            'date' => $this->faker->dateTimeBetween('-1 year', '+1 month')->format('Y-m-d'),
            'time' => $this->faker->time('H:i'),
            'status' => $this->faker->randomElement([
                Order::STATUS_CANCELED,
                Order::STATUS_FULFILLED,
                Order::STATUS_PAID,
                Order::STATUS_NOT_PAID,
            ]),
            'order_type' => $this->faker->randomElement([
                Order::ORDER_TYPE_CASH,
                Order::ORDER_TYPE_PAYMENT,
            ]),
            'length' => $this->faker->numberBetween(30, 240),
        ];
    }
}
