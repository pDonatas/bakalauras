<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Events\OrderTimeChanged;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCallendarRequest;
use App\Models\Order;
use App\Services\Calendar\CalendarService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function __construct(
        private readonly CalendarService $calendarService
    ) {}

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

        $oldTime = $order->time;
        $oldDate = $order->date;

        $order->update([
            'date' => $request->startDate,
            'time' => $request->startTime,
            'length' => $request->length,
        ]);

        event(new OrderTimeChanged($order, $oldTime, $oldDate));

        $order->save();

        return response()->json($order->toCallendarArray());
    }

    public function manage(): View
    {
        $workDay = auth()->user()->workDay;

        $workDays = $workDay ? $workDay->days : [];

        $notifyEvery = auth()->user()->notificationJob->notify_every ?? 0;
        $notifyPeriod = auth()->user()->notificationJob->notify_period ?? 0;

        return view('admin.calendar.manage', compact('workDays', 'workDay', 'notifyEvery', 'notifyPeriod'));
    }

    public function update(UpdateCallendarRequest $request): RedirectResponse
    {
        $workDay = auth()->user()->workDay;

        if (! $workDay) {
            $workDay = auth()->user()->workDay()->create([
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

        $this->calendarService->handleUpdation($workDay);

        return redirect()->back()->with('success', trans('Calendar updated'));
    }
}
