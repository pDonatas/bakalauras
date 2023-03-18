<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class OrdersController extends Controller
{
    public function index(): View
    {
        $orders = auth()->user()->providedOrders()->orderBy('id')->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function edit(Order $order): View
    {
        $services = auth()->user()->providedServicesByShop($order->service->shop)->get();

        return view('admin.orders.edit', compact('order', 'services'));
    }

    public function show(Order $order): View
    {
        $services = auth()->user()->providedServicesByShop($order->service->shop)->get();

        return view('admin.orders.show', compact('order', 'services'));
    }

    public function update(UpdateOrderRequest $request, Order $order): RedirectResponse
    {
        $order->update($request->validated());

        $order->save();

        return redirect()->route('admin.orders.index')->with('success', __('Order updated successfully'));
    }

    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();

        return redirect()->route('admin.orders.index');
    }
}
