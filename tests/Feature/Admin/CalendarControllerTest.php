<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Order;
use App\Models\Service;
use App\Models\Shop;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class CalendarControllerTest extends TestCase
{
    use WithFaker;
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

    public function testIndexReturnsView(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);
        $this->actingAs($user);

        $response = $this->get(route('admin.calendar.index'));

        $response->assertOk();
        $response->assertViewIs('admin.calendar.index');
        $response->assertViewHas('hiddenDays');
        $response->assertViewHas('events');
    }

    public function testUpdateAjaxUpdatesOrder(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);
        $shop = Shop::factory()->create([
            'owner_id' => $user->id,
        ]);

        $category = Category::factory()->create();

        $service = Service::factory()->create([
            'category_id' => $category->id,
            'shop_id' => $shop->id,
            'user_id' => $user->id,
        ]);

        $order = Order::factory()->create(['user_id' => $user->id, 'service_id' => $service->id]);
        $this->actingAs($user);

        $newDate = Carbon::now()->addDay()->format('Y-m-d');
        $newTime = '09:00';
        $newLength = 60;

        $response = $this->post(route('appointments_ajax_update'), [
            'order_id' => $order->id,
            'startDate' => $newDate,
            'startTime' => $newTime,
            'length' => $newLength,
        ]);

        $response->assertOk();
        $response->assertJson([
            'id' => $order->id,
            'title' => $user->name . ' - ' . $service->name,
            'start' => Carbon::createFromFormat('Y-m-d H:i', $newDate . ' ' . $newTime)->toIso8601String(),
            'end'   => Carbon::createFromFormat('Y-m-d H:i', $newDate . ' ' . $newTime)->addMinutes($newLength)->toIso8601String(),
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'date' => $newDate,
            'time' => $newTime,
            'length' => $newLength,
        ]);
    }

    public function testUpdateAjaxReturnsErrorIfOrderNotFound(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);
        $this->actingAs($user);

        $response = $this->post(route('appointments_ajax_update'), [
            'order_id' => 999,
            'startDate' => '2023-04-24',
            'startTime' => '09:00',
            'length' => 60,
        ]);

        $response->assertStatus(JsonResponse::HTTP_NOT_FOUND);
        $response->assertJson(['error' => 'Order not found']);
    }

    public function testManageReturnsView(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);
        $this->actingAs($user);

        $response = $this->get(route('admin.calendar.manage'));

        $response->assertOk();
        $response->assertViewIs('admin.calendar.manage');
        $response->assertViewHas('workDays');
        $response->assertViewHas('workDay');
    }

    public function testUpdateReturnsRedirectResponse(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);
        $this->actingAs($user);

        $response = $this->post(route('admin.calendar.update'), [
            'work_days' => ['1', '3', '5'],
            'from' => '09:00',
            'to' => '18:00',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success', trans('Calendar updated'));
    }
}
