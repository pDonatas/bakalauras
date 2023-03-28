<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCallendarRequest;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(): View
    {
        $events = auth()->user()->providedOrders()->get()->map(function (Order $order) {
            return [
                'id' => $order->id,
                'title' => $order->user->name . ' - ' . $order->service->name,
                'start' => Carbon::createFromFormat('Y-m-d H:i', $order->date . ' ' . $order->time)->toIso8601String(),
                'end'   => Carbon::createFromFormat('Y-m-d H:i', $order->date . ' ' . $order->time)->addMinutes($order->length ?? $order->service->length)->toIso8601String(),
            ];
        })->toArray();

        $workDay = auth()->user()->workDay;

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

        return view('admin.calendar.index', compact('events', 'hiddenDays'));
    }

    public function updateAjax(Request $request): JsonResponse
    {
        $order = Order::find($request->order_id);
        if (! $order) {
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

    public function manage(): View
    {
        $workDay = auth()->user()->workDay;

        $workDays = $workDay ? $workDay->days : [];

        return view('admin.calendar.manage', compact('workDays', 'workDay'));
    }

    public function update(UpdateCallendarRequest $request): RedirectResponse
    {
        $workDay = auth()->user()->workDay;

        if (! $workDay) {
            auth()->user()->workDay()->create([
                'days' => $request->work_days,
                'from' => $request->from,
                'to' => $request->to,
                'user_id' => auth()->id(),
            ]);
        } else {
            $workDay->update([
                'days' => $request->work_days,
                'from' => $request->from,
                'to' => $request->to,
            ]);
        }

        return redirect()->back()->with('success', trans('Calendar updated'));
    }
}
