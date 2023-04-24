<?php

namespace Tests\Feature\Admin;

use App\Models\Order;
use App\Models\Service;
use App\Models\Shop;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestCaseExtended;

class OrdersControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(CategorySeeder::class);

        $user = User::factory()->create([
            'role' => User::ROLE_ADMIN
        ]);

        $this->actingAs($user);
    }

    public function testIndex(): void
    {
        $response = $this->get(route('admin.orders.index'));

        $response->assertOk();
        $response->assertViewIs('admin.orders.index');
        $response->assertViewHas('orders');
    }

    public function testEdit(): void
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create([
            'owner_id' => $user->id,
        ]);

        $service = Service::factory([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
        ])->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'service_id' => $service->id,
        ]);

        $response = $this->get(route('admin.orders.edit', $order->id));

        $response->assertOk();
        $response->assertViewIs('admin.orders.edit');
        $response->assertViewHas('order');
        $response->assertViewHas('services');
    }

    public function testShow(): void
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create([
            'owner_id' => $user->id,
        ]);

        $service = Service::factory([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
        ])->create();

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'service_id' => $service->id,
        ]);

        $response = $this->get(route('admin.orders.show', $order->id));

        $response->assertOk();
        $response->assertViewIs('admin.orders.show');
        $response->assertViewHas('order');
        $response->assertViewHas('services');
    }

    public function testUpdate(): void
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create([
            'owner_id' => $user->id,
        ]);

        $service = Service::factory([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
        ])->create();

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'service_id' => $service->id,
        ]);

        $newDate = $this->faker->date("Y-m-d");
        $newTime = $this->faker->time("H:i");

        $response = $this->put(route('admin.orders.update', $order->id), [
            'date' => $newDate,
            'time' => $newTime,
            'order_type' => $order->order_type,
            'service_id' => $order->service_id,
            'status' => $order->status,
        ]);

        $response->assertRedirect(route('admin.orders.index'));
        $response->assertSessionHas('success', __('Order updated successfully'));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'date' => $newDate,
            'time' => $newTime,
        ]);
    }

    public function testDestroy(): void
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create([
            'owner_id' => $user->id,
        ]);

        $service = Service::factory([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
        ])->create();

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'service_id' => $service->id,
        ]);

        $response = $this->delete(route('admin.orders.destroy', $order->id));

        $response->assertRedirect(route('admin.orders.index'));

        $this->assertModelMissing($order);
    }
}
