<?php

declare(strict_types=1);

namespace App\Services\Calendar;

use App\Events\OrderTimeChanged;
use App\Http\Services\TimeCalculationService;
use App\Models\Order;
use App\Models\Service;
use App\Models\WorkDay;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class CalendarService
{
    public function __construct(
        private readonly TimeCalculationService $timeCalculationService
    ) {}

    public function handleUpdation(WorkDay $workDay): void
    {
        $provider = $workDay->user;
        $orders = $provider->providedOrders;
        $enabledDays = $workDay->days;

        foreach ($orders as $order) {
            $date = $order->date;
            $time = $order->time;

            $weekDay = Carbon::createFromFormat('Y-m-d', $date)->dayOfWeekIso;

            if (! in_array($weekDay, $enabledDays)) {
                $newDate = $this->calculateNextAvailableDay($date, $time, $workDay, $order->service);

                $oldDate = $order->date;
                $oldTime = $order->time;
                $order->date = $newDate[0];
                $order->time = $newDate[1];

                $order->save();
                event(new OrderTimeChanged($order, $oldTime, $oldDate));
            }
        }
    }

    /**
     * @return string[]
     */
    private function calculateNextAvailableDay(string $date, string $time, WorkDay $workDay, Service $service): array
    {
        $from = Carbon::createFromFormat('Y-m-d', $date);
        $to = Carbon::createFromFormat('Y-m-d', $date)->addWeeks();

        $interval = CarbonPeriod::create($from, $to);

        foreach ($interval as $intervalDate) {
            $orders = Order::where('date', $intervalDate->format('Y-m-d'))
                ->where('service_id', $service->id)
                ->get();

            $enabledTimes = $this->timeCalculationService->calculate([$workDay->from, $workDay->to], $orders);

            if (in_array($time, $enabledTimes)) {
                return [
                    $intervalDate->format('Y-m-d'),
                    $time,
                ];
            }
        }

        $time = Carbon::createFromFormat('H:i', $time);
        $time->addMinutes(30);

        $time = $time->format('H:i');

        return $this->calculateNextAvailableDay($date, $time, $workDay, $service);
    }
}
