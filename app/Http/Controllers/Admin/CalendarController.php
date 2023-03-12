<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        $events = auth()->user()->providedOrders()->get()->map(function (Order $order) {
            return [
                'id' => $order->id,
                'title' => $order->user->name . ' - ' . $order->service->name,
                'start' => Carbon::createFromFormat('Y-m-d H:i', $order->date . ' ' . $order->time)->toIso8601String(),
                'end'   => Carbon::createFromFormat('Y-m-d H:i', $order->date . ' ' . $order->time)->addMinutes($order->length ?? $order->service->length)->toIso8601String()
            ];
        })->toArray();

        return view('admin.calendar.index', compact('events'));
    }

    public function updateAjax(Request $request): JsonResponse
    {
        $order = Order::find($request->order_id);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $order->update([
            'date' => $request->startDate,
            'time' => $request->startTime,
            'length' => $request->length,
        ]);

        $order->save();

        return response()->json($order->toCallendarArray());
    }
}
