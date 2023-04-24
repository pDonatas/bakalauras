<?php

namespace Tests\Feature\Admin;

use App\Models\Shop;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ShopControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(CategorySeeder::class);

        $user = User::factory()->create([
            'role' => User::ROLE_ADMIN
        ]);

        $this->actingAs($user);
    }

    public function testItShouldReturnTheShopsIndexViewForAuthenticatedUsers(): void
    {
        // Act
        $response = $this->get(route('admin.shops.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('admin.shops.index');
        $response->assertViewHas(['shops', 'users']);
    }

    public function testItShouldRedirectUnauthenticatedUsersToTheLoginPage(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        // Act
        $response = $this->get(route('admin.shops.index'));

        // Assert
        $response->assertStatus(302);
        $response->assertRedirect(route('index'));
    }

    public function testItShouldCreateANewShop(): void
    {
        // Arrange
        Storage::fake('public');
        $shopData = Shop::factory()->make()->toArray();
        $image = $this->faker->image();
        $shopData['photo'] = new UploadedFile($image, "test.png", null, null, true);

        // Act
        $response = $this->post(route('admin.shops.store'), $shopData);

        // Assert
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.shops.index'));
        $this->assertDatabaseHas('shops', ['company_name' => $shopData['company_name']]);
    }

    public function testItShouldShowAShop(): void
    {
        // Arrange
        $shop = Shop::factory()->create();

        // Act
        $response = $this->get(route('admin.shops.show', $shop));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('admin.shops.show');
        $response->assertViewHas('shop');
    }

    public function testItShouldReturnTheEditViewForAuthenticatedUsers(): void
    {
        // Arrange
        $shop = Shop::factory()->create();

        // Act
        $response = $this->get(route('admin.shops.edit', $shop));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('admin.shops.edit');
        $response->assertViewHas(['shop', 'users']);
    }

    public function testItShouldUpdateAShop(): void
    {
        // Arrange
        $shop = Shop::factory()->create();
        Storage::fake('public');
        $newData = Shop::factory()->make()->toArray();
        $image = $this->faker->image();
        $newData['photo'] = new UploadedFile($image, "test.png", null, null, true);

        // Act
        $response = $this->put(route('admin.shops.update', $shop), $newData);

        // Assert
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.shops.show', $shop));
        $this->assertDatabaseHas('shops', ['id' => $shop->id, 'company_name' => $newData['company_name']]);
    }

    public function testItShouldDeleteAShop(): void
    {
        // Arrange
        $shop = Shop::factory()->create();

        // Act
        $response = $this->delete(route('admin.shops.destroy', $shop));

        // Assert
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.shops.index'));
        $this->assertDatabaseMissing('shops', ['id' => $shop->id]);
    }
}

