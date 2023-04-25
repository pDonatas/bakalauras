<?php

namespace Tests\Feature\Admin;

use App\Models\Service;
use App\Models\Shop;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(CategorySeeder::class);

        $user = User::factory()->create([
            'role' => User::ROLE_ADMIN
        ]);

        $this->actingAs($user);
    }

    public function testItShouldReturnTheServicesIndexViewForAuthenticatedUsers(): void
    {
        // Arrange
        $user = User::factory()->create();
        $shop = Shop::factory()->create([
            'owner_id' => $user->id,
        ]);

        // Act
        $response = $this->get(route('admin.services.index', $shop->id));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('admin.shops.services.index');
        $response->assertViewHas(['shop', 'services', 'categories']);
    }

    public function testItShouldReturnTheEditViewForAuthenticatedUsers(): void
    {
        // Arrange
        $shop = Shop::factory()->create();
        $user = User::factory()->create();
        $service = Service::factory()->create(['shop_id' => $shop->id, 'user_id' => $user->id]);

        // Act
        $response = $this->get(route('admin.services.edit', [$shop, $service]));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('admin.shops.services.edit');
        $response->assertViewHas(['shop', 'service', 'categories']);
    }

    public function testItShouldReturnARedirectResponseAfterCreatingAService(): void
    {
        // Arrange
        $shop = Shop::factory()->create();
        $user = User::factory()->create();
        $serviceData = Service::factory()->make([
            'user_id' => $user->id,
        ])->toArray();

        // Act
        $response = $this->post(route('admin.services.store', $shop), $serviceData);

        // Assert
        $response->assertStatus(302);
        $this->assertDatabaseHas('services', $serviceData);
    }

    public function testItShouldReturnARedirectResponseAfterUpdatingAService(): void
    {
        // Arrange
        $user = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);
        $shop = Shop::factory()->create();
        $service = Service::factory()->create(['shop_id' => $shop->id, 'user_id' => $user->id]);
        $newData = Service::factory()->make([
            'user_id' => $user->id,
            'category_id' => $service->category_id,
            'length' => $service->length,
        ])->toArray();

        // Act
        $response = $this->put(route('admin.services.update', [$shop, $service]), $newData);

        // Assert
        $response->assertStatus(302);
        $this->assertDatabaseHas('services', $newData);
    }
}
