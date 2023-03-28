<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class TimeCalculationService
{
    /**
     * @param array<int, string>          $workTimes
     * @param Collection<int, Order>|null $orders
     *
     * @return array<int, string>
     */
    public function calculate(?array $workTimes, ?Collection $orders): array
    {
        $enabledTimes = [];
        $step = 30;

        $workTimes = $workTimes ?? [0, 0];
        $workTimes[0] = Carbon::createFromTimeString($workTimes[0]);
        $workTimes[1] = Carbon::createFromTimeString($workTimes[1]);

        $orders = $orders ?? [];

        $current = $workTimes[0]->copy();

        while ($current->lte($workTimes[1])) {
            $enabledTimes[] = $current->format('H:i');
            $current->addMinutes($step);
        }

        foreach ($orders as $order) {
            $orderTimeStart = Carbon::createFromTimeString($order->time);
            $orderTimeEnd = $orderTimeStart->copy()->addMinutes($order->service->duration);

            $current = $workTimes[0]->copy();
            while ($current->lte($workTimes[1])) {
                if ($current->between($orderTimeStart, $orderTimeEnd)) {
                    $key = array_search($current->format('H:i'), $enabledTimes);
                    unset($enabledTimes[$key]);
                }
                $current->addMinutes($step);
            }
        }

        return $enabledTimes;
    }
}
