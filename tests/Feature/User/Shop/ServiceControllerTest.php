<?php

declare(strict_types=1);

namespace Tests\Feature\User\Shop;

use App\Http\Services\TimeCalculationService;
use App\Http\Controllers\User\Shop\ServiceController;
use App\Models\Order;
use App\Models\Service;
use App\Models\Shop;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(CategorySeeder::class);
    }

    public function testPhotosMethodReturnsJsonResponse(): void
    {
        $service = Service::factory()->create([
            'user_id' => User::factory()->create()->id,
            'shop_id' => Shop::factory()->create([
                'owner_id' => User::factory()->create()->id,
            ])->id,
        ]);
        $response = (new ServiceController(new TimeCalculationService()))->photos($service);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function testTimeMethodReturnsJsonResponse(): void
    {
        $shop = Shop::factory()->create([
            'owner_id' => User::factory()->create()->id,
        ]);

        $service = Service::factory()->create([
            'user_id' => User::factory()->create()->id,
            'shop_id' => $shop->id,
        ]);

        $request = new Request(['date' => '2023-05-01']);
        $response = (new ServiceController(new TimeCalculationService()))->time($request, $service);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function testTimeMethodReturnsEnabledTimes(): void
    {
        $shop = Shop::factory()->create([
            'owner_id' => User::factory()->create()->id,
        ]);
        $service = Service::factory()->create([
            'shop_id' => $shop->id,
            'user_id' => User::factory()->create()->id,
        ]);
        $order1 = Order::factory()->create([
            'service_id' => $service->id,
            'date' => '2023-05-01',
            'time' => '09:00:00',
            'user_id' => User::factory()->create()->id,
        ]);
        $order2 = Order::factory()->create([
            'service_id' => $service->id,
            'date' => '2023-05-01',
            'time' => '10:00:00',
            'user_id' => User::factory()->create()->id,
        ]);
        $request = new Request(['date' => '2023-05-01']);
        $response = (new ServiceController(new TimeCalculationService()))->time($request, $service);
        $enabledTimes = json_decode($response->content());
        $this->assertContains('00:00', $enabledTimes);
        $this->assertNotContains('09:00:00', $enabledTimes);
        $this->assertNotContains('10:00:00', $enabledTimes);
        $this->assertNotContains('11:00:00', $enabledTimes);
    }
}
