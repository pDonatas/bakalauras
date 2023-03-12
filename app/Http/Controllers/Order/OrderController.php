<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
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
        return view('user.orders.create', compact('service'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request, Service $service, PaymentServiceInterface $paymentService)
    {
        $order = Order::make($request->validated());
        $order->user()->associate(auth()->user());
        $order->service()->associate($service);
        $order->save();

        $url = route('user.orders');

        if ($order->order_type == Order::ORDER_TYPE_PAYMENT) {
            $url = $paymentService->pay($order->id, $service->price);
        }

        return redirect($url);
    }

    public function success(Request $request): View
    {
        if ($request->data) {
            $data = base64_decode($request->data);
            if (!$data) {
                return view('user.orders.fail');
            }

            parse_str($data, $parsed);
            $order = Order::find($parsed['orderid']);
            $order->status = Order::STATUS_PAID;
            $order->save();
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
}
