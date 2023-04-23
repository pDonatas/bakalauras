<?php

namespace Tests\Feature\Main;

use App\Enums\City;
use App\Models\Bookmark;
use App\Models\Category;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MainControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testItShouldReturnTheIndexView()
    {
        // Arrange
        $response = $this->get(route('index'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('index');
        $response->assertViewHasAll(['shops', 'categories', 'cities', 'favorites']);
    }

    public function testItShouldFilterShopsByCategoryId()
    {
        // Arrange
        $owner = User::factory()->create([
            'role' => User::ROLE_BARBER
        ]);
        $category = Category::factory()->create();
        $shop1 = Shop::factory()->create([
            'owner_id' => $owner->id,
            'company_address' => City::VARENA
        ]);
        $shop1->services()->create(['category_id' => $category->id, 'user_id' => $owner->id]);

        // Act
        $response = $this->get(route('index', ['category_id' => $category->id]));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('index');
        $response->assertViewHas('shops', function ($shops) use ($shop1) {
            return $shops->count() === 1 && $shops->first()->id === $shop1->id;
        });
    }

    public function testItShouldFilterShopsByCompanyName()
    {
        // Arrange
        $owner = User::factory()->create([
            'role' => User::ROLE_BARBER
        ]);
        $shop1 = Shop::factory()->create(['company_name' => 'Test company', 'owner_id' => $owner->id]);

        // Act
        $response = $this->get(route('index', ['company_name' => 'Test company']));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('index');
        $response->assertViewHas('shops', function ($shops) use ($shop1) {
            return $shops->count() === 1 && $shops->first()->id === $shop1->id;
        });
    }

    public function testItShouldFilterShopsByName()
    {
        // Arrange
        $owner = User::factory()->create([
            'role' => User::ROLE_BARBER
        ]);
        $shop1 = Shop::factory()->create(['company_name' => 'Test shop', 'owner_id' => $owner->id]);

        // Act
        $response = $this->get(route('index', ['company_name' => 'Test shop']));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('index');
        $response->assertViewHas('shops', function ($shops) use ($shop1) {
            return $shops->count() === 1 && $shops->first()->id === $shop1->id;
        });
    }

    public function testItShouldFilterShopsByCity()
    {
        // Arrange
        $owner = User::factory()->create([
            'role' => User::ROLE_BARBER
        ]);
        $shop1 = Shop::factory()->create(['company_address' => City::VILNIUS, 'owner_id' => $owner->id]);

        // Act
        $response = $this->get(route('index', ['city' => City::VILNIUS]));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('index');
        $response->assertViewHas('shops', function ($shops) use ($shop1) {
            return $shops->count() === 1 && $shops->first()->id === $shop1->id;
        });
    }

    public function testItShouldFilterShopsByPriceFrom()
    {
        // Arrange
        $owner = User::factory()->create([
            'role' => User::ROLE_BARBER
        ]);
        $category = Category::factory()->create();
        $shop1 = Shop::factory()->create([
            'owner_id' => $owner->id
        ]);
        $shop1->services()->create(['category_id' => $category->id, 'price' => 50, 'user_id' => $owner->id]);

        $owner = User::factory()->create([
            'role' => User::ROLE_BARBER
        ]);
        $shop2 = Shop::factory()->create([
            'owner_id' => $owner->id
        ]);
        $shop2->services()->create(['category_id' => $category->id, 'price' => 100, 'user_id' => $owner->id]);

        // Act
        $response = $this->get(route('index', ['price_from' => 75]));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('index');
        $response->assertViewHas('shops', function ($shops) use ($shop1, $shop2) {
            return $shops->count() === 1 && $shops->first()->id === $shop2->id;
        });
    }

    public function testItShouldFilterShopsByPriceTo()
    {
        // Arrange
        $user = User::factory()->create([
            'role' => User::ROLE_BARBER
        ]);
        $category = Category::factory()->create();
        $shop1 = Shop::factory()->create([
            'owner_id' => $user->id
        ]);
        $shop1->services()->create(['category_id' => $category->id, 'price' => 50, 'user_id' => $user->id]);
        $shop2 = Shop::factory()->create();
        $shop2->services()->create(['category_id' => $category->id, 'price' => 100, 'user_id' => $user->id]);

        // Act
        $response = $this->get(route('index', ['price_to' => 75]));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('index');
        $response->assertViewHas('shops', function ($shops) use ($shop1, $shop2) {
            return $shops->count() === 1 && $shops->first()->id === $shop1->id;
        });
    }

    public function testItShouldFilterFavoriteShops()
    {
        // Arrange
        $user = User::factory()->create([
            'role' => User::ROLE_BARBER
        ]);
        $category = Category::factory()->create();
        $shop1 = Shop::factory([
            'owner_id' => $user->id
        ])->create();
        $shop1->services()->create(['category_id' => $category->id, 'user_id' => $user->id]);

        $user = User::factory()->create([
            'role' => User::ROLE_BARBER
        ]);
        $shop2 = Shop::factory()->create([
            'owner_id' => $user->id
        ]);
        $shop2->services()->create(['category_id' => $category->id, 'user_id' => $user->id]);

        $user = User::factory()->create();
        Bookmark::create([
            'user_id' => $user->id,
            'shop_id' => $shop1->id
        ]);

        // Act
        $response = $this->actingAs($user)->get(route('index'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('index');
        $response->assertViewHas('favorites', function ($favorites) use ($shop1, $shop2) {
            return $favorites->count() === 1 && $favorites->first()->id === $shop1->id;
        });
    }
}
