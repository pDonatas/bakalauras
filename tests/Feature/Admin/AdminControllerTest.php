<?php

namespace Tests\Feature\Admin;

use App\Http\Services\ShopService;
use App\Models\Category;
use App\Models\Order;
use App\Models\Service;
use App\Models\Shop;
use App\Models\User;
use App\Services\DashboardService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;
    public function testAdminCanAccessDashboard(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $response = $this->actingAs($user)->get(route('admin.index'));

        $response->assertSuccessful();
        $response->assertViewIs('admin.dashboard');
    }

    public function testNonAdminUserIsRedirectedToHome(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_USER,
        ]);

        $response = $this->actingAs($user)->get(route('admin.index'));

        $response->assertRedirect(route('index'));
    }

    public function testDashboardShowsCorrectInformation(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $shop = Shop::factory()->create([
            'owner_id' => $admin->id,
        ]);

        $category = Category::factory()->create();

        Service::factory()->create([
            'category_id' => $category->id,
            'shop_id' => $shop->id,
            'user_id' => $admin->id,
        ]);

        $order = Order::factory()->create([
            'user_id' => $admin->id,
        ]);
        $order->save();

        $dashboardServiceMock = $this->createMock(DashboardService::class);
        $dashboardServiceMock->expects($this->once())
            ->method('getAverageRating')
            ->willReturn(4.5);

        $dashboardServiceMock->expects($this->once())
            ->method('getUniqueClientsCount')
            ->with(null)
            ->willReturn(5);

        $shopServiceMock = $this->createMock(ShopService::class);
        $shopServiceMock->expects($this->once())
            ->method('countSales')
            ->withAnyParameters()
            ->willReturn([
                1 => 100,
                2 => 100,
                3 => 100,
                4 => 100,
                5 => 100,
                6 => 100,
                7 => 100,
                8 => 100,
                9 => 100,
                10 => 100,
                11 => 100,
                12 => 100
            ]);

        app()->instance(DashboardService::class, $dashboardServiceMock);
        app()->instance(ShopService::class, $shopServiceMock);

        $response = $this->actingAs($admin)->get(route('admin.index'));

        $response->assertSuccessful();

        $response->assertViewHasAll([
            'shopsCount' => 1,
            'averageRating' => 4.5,
            'ordersCount' => 1,
            'uniqueClientsCount' => 5,
        ]);
    }
}
