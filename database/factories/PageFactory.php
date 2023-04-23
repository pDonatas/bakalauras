<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Page;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageFactory extends Factory
{
    protected $model = Page::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'shop_id' => $this->faker->randomElement(Shop::pluck('id')),
        ];
    }
}
