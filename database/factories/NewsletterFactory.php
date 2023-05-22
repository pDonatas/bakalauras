<?php

namespace Database\Factories;

use App\Models\Newsletter;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsletterFactory extends Factory
{
    protected $model = Newsletter::class;

    public function definition(): array
    {
        return [
            'text' => $this->faker->text,
            'sent_at' => $this->faker->dateTime,
            'created_by' => $this->faker->numberBetween(1, 10),
        ];
    }
}
