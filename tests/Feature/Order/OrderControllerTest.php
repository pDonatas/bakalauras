<?php

declare(strict_types=1);

namespace Tests\Feature\Order;

use App\Events\OrderPaid;
use App\Events\OrderTimeChanged;
use App\Http\Controllers\Order\OrderController;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrdersRequest;
use App\Models\Order;
use App\Models\Service;
use App\Models\Shop;
use App\Models\User;
use App\Models\WorkDay;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Mockery;
use Symfony\Component\HttpFoundation\ParameterBag;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private OrderController $orderController;
    private Service $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->orderController = new OrderController();
        $this->seed(CategorySeeder::class);
        $shop = Shop::factory()->create([
            'owner_id' => User::factory()->create()->id
        ]);

        $worker = User::factory()->create([
            'role' => User::ROLE_BARBER
        ]);

        WorkDay::factory()->create([
            'days' => [1, 2, 3, 4, 5, 6, 7],
            'user_id' => $worker->id,
        ]);

        $this->service = Service::factory()->create([
            'user_id' => $worker->id,
            'price' => 10.0,
            'shop_id' => $shop->id,
        ]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    public function testIndexReturnsViewWithPaginatedOrders(): void
    {
        $user = User::factory()->create();
        Order::factory()->count(15)->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $view = $this->orderController->index();

        assert(isset($view->orders));

        $this->assertInstanceOf(View::class, $view);
        $this->assertInstanceOf(LengthAwarePaginator::class, $view->orders);
        $this->assertCount(10, $view->orders);
    }

    public function testCreateReturnsViewWithCorrectData(): void
    {
        $view = $this->orderController->create($this->service);

        $this->assertInstanceOf(View::class, $view);
        assert(isset($view->service));
        assert(isset($view->hiddenDays));
        $this->assertEquals($this->service, $view->service);
        $this->assertEquals([], $view->hiddenDays);
    }

    public function testSuccessUpdatesTheOrderStatusAndFiresOrderPaidEvent(): void
    {
        Event::fake();
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'service_id' => $this->service->id,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('orders.success', ['data' => base64_encode('orderid=' . $order->id)]));

        $response->assertViewIs('user.orders.success');

        $updatedOrder = Order::find($order->id);

        $this->assertEquals(Order::STATUS_PAID, $updatedOrder->status);
        Event::assertDispatched(OrderPaid::class, function ($event) use ($updatedOrder) {
            return $event->getOrder()->id === $updatedOrder->id;
        });
    }

    public function testSuccessReturnsFailViewIfRequestDataIsInvalid(): void
    {
        $request = new Request();
        $request->merge([
            'data' => 'invalid-data',
        ]);

        $response = $this->orderController->success($request);

        $this->assertInstanceOf(View::class, $response);
        $this->assertEquals('user.orders.fail', $response->getName());
    }

    public function testFailUpdatesOrderStatusAndReturnsFailView(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $order = Order::factory()->create(['user_id' => $user->id]);

        $response = $this->orderController->fail();

        $this->assertInstanceOf(View::class, $response);
        $this->assertEquals('user.orders.fail', $response->getName());

        $updatedOrder = Order::find($order->id);

        $this->assertEquals(Order::STATUS_CANCELED, $updatedOrder->status);
    }

    public function testCancelUpdatesOrderStatusAndRedirectsToIndex(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $order = Order::factory()->create(['user_id' => $user->id]);

        $response = $this->orderController->cancel($order);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('user.orders'), $response->getTargetUrl());

        $updatedOrder = Order::find($order->id);

        $this->assertEquals(Order::STATUS_CANCELED, $updatedOrder->status);
    }

    public function testEditReturnsViewWithCorrectData(): void
    {
        $user = User::factory()->create();
        WorkDay::factory()->create([
            'days' => [0, 1, 2, 3, 4, 5, 6],
            'user_id' => $user->id,
        ]);
        $this->actingAs($user);
        $order = Order::factory()->create(['user_id' => $user->id]);

        $view = $this->orderController->edit($order);

        $this->assertInstanceOf(View::class, $view);

        assert(isset($view->order));
        assert(isset($view->hiddenDays));
        assert(isset($view->service));

        $this->assertEquals($order, $view->order);
        $this->assertEquals([], $view->hiddenDays);
        $this->assertEquals($order->service, $view->service);
    }

    public function testUpdateUpdatesOrderAndRedirectsToIndex(): void
    {
        Event::fake();
        $user = User::factory()->create();
        $this->actingAs($user);
        $order = Order::factory()->create(['user_id' => $user->id, 'service_id' => $this->service->id]);

        $updateOrderRequest = [
            'time' => '23:00',
            'date' => '2022-08-01',
            'order_type' => $order->order_type,
        ];

        $response = $this->put(route('orders.update', $order->id), $updateOrderRequest);
        $response->isRedirect(route('user.orders'));
        $response->assertSessionHasNoErrors();

        $updatedOrder = Order::find($order->id);

        $this->assertEquals('23:00', $updatedOrder->time);
        $this->assertEquals('2022-08-01', $updatedOrder->date);

        Event::assertDispatched(OrderTimeChanged::class, function ($event) use ($updatedOrder) {
            return $event->getOrder()->id === $updatedOrder->id;
        });
    }

    public function testUpdateUploadsCurrentPhotoFile(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $this->actingAs($user);
        $order = Order::factory()->create(['user_id' => $user->id, 'service_id' => $this->service->id]);

        $updateOrderRequest = [
            'name' => 'John Smith',
            'time' => '4:00 PM',
            'date' => '2022-08-01',
            'current_photo_file' => $this->createTempFile(),
        ];

        $response = $this->post(route('orders.update', $order), $updateOrderRequest);

        $response->assertOk();

        $updatedOrder = Order::find($order->id);

        $this->assertInstanceOf(Order::class, $updatedOrder);
    }

    public function testShowReturnsViewWithCorrectData(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_BARBER
        ]);

        $this->actingAs($user);
        $order = Order::factory()->create(['user_id' => $user->id, 'service_id' => $this->service->id]);

        $view = $this->orderController->show($order);

        $this->assertInstanceOf(View::class, $view);

        assert(isset($view->order));
        assert(isset($view->hiddenDays));
        assert(isset($view->service));

        $this->assertEquals($order, $view->order);
        $this->assertEquals([], $view->hiddenDays);
        $this->assertEquals($order->service, $view->service);
    }

    public function testReviewReturnsViewWithCorrectData(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $order = Order::factory()->create(['user_id' => $user->id]);

        $view = $this->orderController->review($order);

        $this->assertInstanceOf(View::class, $view);

        assert(isset($view->shop));

        $this->assertEquals($order->service->shop, $view->shop);
    }

    /**
     * Create temporary file.
     */
    private function createTempFile(): UploadedFile
    {
        $file = $this->faker->image;

        return new UploadedFile($file, 'temp-file.png', null, null, true);
    }
}



