<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Shop>
 */
class ShopFactory extends Factory
{
    public function definition(): array
    {
        $image = fake()->image();

        $newName = time() . '_' . Str::random(10) . '.jpg';

        $newPath = 'images/shops/';

        \Storage::disk('public')->putFileAs($newPath, $image, $newName);

        return [
            'company_name' => fake()->company(),
            'description' => fake()->text(),
            'company_code' => fake()->companySuffix(),
            'company_address' => fake()->address(),
            'company_phone' => fake()->phoneNumber(),
            'photo' => '/storage/images/shops/' . $newName,
            'owner_id' => fake()->randomElement(User::all()->pluck('id')->toArray()),
        ];
    }
}
