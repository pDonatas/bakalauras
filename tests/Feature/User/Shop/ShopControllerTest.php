<?php

declare(strict_types=1);

namespace Tests\Feature\User\Shop;

use App\Http\Controllers\User\Shop\ShopController;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\View\View;
use Tests\TestCase;

class ShopControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testShowMethodReturnsViewWithShopAndPhotos(): void
    {
        // create a shop
        $shop = Shop::factory()->create([
            'owner_id' => User::factory()->create()->id,
        ]);

        // create services for the shop
        $services = $shop->services()->createMany([
            ['name' => $this->faker->word, 'price' => 100, 'user_id' => User::factory()->create()->id],
            ['name' => $this->faker->word, 'price' => 200, 'user_id' => User::factory()->create()->id],
            ['name' => $this->faker->word, 'price' => 300, 'user_id' => User::factory()->create()->id],
            ['name' => $this->faker->word, 'price' => 400, 'user_id' => User::factory()->create()->id],
            ['name' => $this->faker->word, 'price' => 500, 'user_id' => User::factory()->create()->id],
        ]);

        // add photos to the services
        foreach ($services as $service) {
            $service->photos()->createMany([
                ['path' => $this->faker->imageUrl()],
                ['path' => $this->faker->imageUrl()],
                ['path' => $this->faker->imageUrl()],
            ]);
        }

        // call the show method of the ShopController
        $response = $this->get(route('shop.show', $shop->id));

        // assert that the response status is 200
        $response->assertOk();

        // assert that the view returned by the show method is an instance of View
        $this->assertInstanceOf(View::class, $response->original);

        // assert that the view has a shop variable
        $this->assertArrayHasKey('shop', $response->original->getData());

        // assert that the shop variable is the shop we created
        $this->assertEquals($shop->id, $response->original->getData()['shop']->id);

        // assert that the view has a photos variable
        $this->assertArrayHasKey('photos', $response->original->getData());

        // assert that the photos variable is an array with up to 4 items
        $this->assertLessThanOrEqual(4, count($response->original->getData()['photos']));
    }
}
