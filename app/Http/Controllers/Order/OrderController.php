<?php

declare(strict_types=1);

namespace App\Http\Controllers\Order;

use App\Events\OrderPaid;
use App\Events\OrderTimeChanged;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrdersRequest;
use App\Models\Order;
use App\Models\Service;
use App\Services\Payments\PaymentServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = auth()->user()->orders()->paginate(10);

        return view('user.orders.list', compact('orders'));
    }

    public function create(Service $service): View
    {
        $workDay = $service->worker->workDay;

        $workDays = $workDay ? $workDay->days : [];

        // calculate days to hide
        $hiddenDays = [];
        for ($i = 1; $i <= 7; $i++) {
            if (! in_array($i, $workDays)) {
                if (7 == $i) {
                    $hiddenDays[] = 0;
                } else {
                    $hiddenDays[] = $i;
                }
            }
        }

        return view('user.orders.create', compact('service', 'hiddenDays'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request, Service $service, PaymentServiceInterface $paymentService): RedirectResponse
    {
        $currentPhoto = null;
        if ($request->file('ai_photo_file')) {
            $fileName = 'expected_photo_' . time() . '.png';
            if (! is_dir(storage_path('app/public/expected_photos'))) {
                mkdir(storage_path('app/public/expected_photos'), 0777, true);
            }

            $request->file('ai_photo_file')->storeAs('public/expected_photos', $fileName);

            $currentPhoto = '/storage/expected_photos/' . $fileName;
        }
        $file = '';
        if (isset($request['current_photo']) && $request['current_photo']) {
            // convert base64 to file
            $fileName = 'current_photo_' . time() . '.png';
            if (! is_dir(storage_path('app/public/current_photos'))) {
                mkdir(storage_path('app/public/current_photos'), 0777, true);
            }

            $path = storage_path('app/public/current_photos/' . $fileName);
            $data = explode(',', $request['current_photo']);
            if (! isset($data[1])) {
                return redirect()->back()->with('error', 'Invalid image');
            }

            $data = base64_decode($data[1]);
            file_put_contents($path, $data);

            $file = '/storage/current_photos/' . $fileName;
        }

        if (isset($request['current_photo_file']) && $request['current_photo_file']) {
            $file = $request['current_photo_file']->store('public/current_photos');

            $file = str_replace('public', '/storage', $file);
        }

        $request = $request->validated();
        unset($request['current_photo'], $request['current_photo_file'], $request['ai_photo_file']);
        if ($currentPhoto) {
            $request['ai_photo'] = $currentPhoto;
        }

        $order = new Order($request);
        $order->user()->associate(auth()->user());
        $order->service()->associate($service);

        if ('' !== ! $file) {
            $order->current_photo = $file;
        }

        $order->save();

        $url = route('user.orders');

        if (Order::ORDER_TYPE_PAYMENT == $order->order_type) {
            $url = $paymentService->pay($order->id, $service->price);
        }

        return redirect($url);
    }

    public function success(Request $request): View
    {
        if ($request->data) {
            $data = base64_decode($request->data);
            if (! $data || ! str_contains($data, 'orderid')) {
                return view('user.orders.fail');
            }

            parse_str($data, $parsed);
            $order = Order::find($parsed['orderid']);
            $order->status = Order::STATUS_PAID;
            $order->save();

            event(new OrderPaid($order));
        }

        return view('user.orders.success');
    }

    public function fail(): View
    {
        $order = auth()->user()->orders()->latest()->first();

        if ($order) {
            $order->status = Order::STATUS_CANCELED;
            $order->save();
        }

        return view('user.orders.fail');
    }

    public function cancel(Order $order): RedirectResponse
    {
        $order->status = Order::STATUS_CANCELED;
        $order->save();

        return redirect()->route('user.orders');
    }

    public function edit(Order $order): View
    {
        $workDay = $order->service->worker->workDay;
        $service = $order->service;

        $workDays = $workDay ? $workDay->days : [];

        // calculate days to hide
        $hiddenDays = [];
        for ($i = 1; $i <= 7; $i++) {
            if (! in_array($i, $workDays)) {
                if (7 == $i) {
                    $hiddenDays[] = 0;
                } else {
                    $hiddenDays[] = $i;
                }
            }
        }

        return view('user.orders.edit', compact('order', 'hiddenDays', 'service'));
    }

    public function update(UpdateOrdersRequest $request, Order $order): RedirectResponse
    {
        $oldTime = $order->time;
        $oldDate = $order->date;
        $order->fill($request->validated());

        if ($oldTime != $order->time || $oldDate != $order->date) {
            event(new OrderTimeChanged($order, $oldTime, $oldDate));
        }

        $file = '';
        if (isset($request['current_photo']) && $request['current_photo']) {
            // convert base64 to file
            $fileName = 'current_photo_' . time() . '.png';
            if (! is_dir(storage_path('app/public/current_photos'))) {
                mkdir(storage_path('app/public/current_photos'), 0777, true);
            }

            $path = storage_path('app/public/current_photos/' . $fileName);
            $data = explode(',', $request['current_photo']);
            $data = base64_decode($data[1]);
            file_put_contents($path, $data);

            $file = '/storage/current_photos/' . $fileName;
        }

        if (isset($request['current_photo_file']) && $request['current_photo_file']) {
            $file = $request['current_photo_file']->store('public/current_photos');

            $file = str_replace('public', '/storage', $file);
        }

        if ('' !== $file) {
            $order->current_photo = $file;
        }

        $order->save();

        return redirect()->route('user.orders');
    }

    public function show(Order $order): View
    {
        $workDay = $order->service->worker->workDay;
        $service = $order->service;

        $workDays = $workDay ? $workDay->days : [];

        // calculate days to hide
        $hiddenDays = [];
        for ($i = 1; $i <= 7; $i++) {
            if (! in_array($i, $workDays)) {
                if (7 == $i) {
                    $hiddenDays[] = 0;
                } else {
                    $hiddenDays[] = $i;
                }
            }
        }

        return view('user.orders.show', compact('order', 'hiddenDays', 'service'));
    }

    public function review(Order $order): View
    {
        $shop = $order->service->shop;

        return view('user.orders.review', compact('shop'));
    }
}
