<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Models\WorkDay;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WorkDay>
 */
class WorkDayFactory extends Factory
{
    protected $model = WorkDay::class;

    public function definition(): array
    {
        return [
            'days' => $this->faker->randomElements([0, 1, 2, 3, 4, 5, 6]),
            'user_id' => User::inRandomOrder()->first()->id,
        ];
    }
}
