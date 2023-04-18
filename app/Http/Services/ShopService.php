<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Enums\Month;
use App\Models\Order;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ShopService
{
    /**
     * @return array<string, int>
     */
    public function countSalesByMonth(int $shopId, int $months = 12): array
    {
        $relations = Order::query()
            ->selectRaw('COUNT(*) as count, MONTH(created_at) as month')
            ->whereRelation('shop', 'shops.id', $shopId)
            ->where('created_at', '>=', Carbon::now()->subMonths($months))
            ->groupByRaw('MONTH(created_at)')
            ->get();

        $countsByMonth = [];
        for ($i = 1; $i <= $months; $i++) {
            $countsByMonth[Month::getMonthName($i)] = 0;
        }

        foreach ($relations as $relation) {
            /** @phpstan-ignore-next-line  */
            $countsByMonth[Month::getMonthName($relation->month)] = $relation->count;
        }

        return $countsByMonth;
    }

    /**
     * @return array<string, int>
     */
    public function countSales(\DateTime $salesDateFrom, \DateTime $salesDateTo, int $shopId): array
    {
        $relations = Order::query()
            ->selectRaw('COUNT(*) as count, DATE_FORMAT(date, "%Y-%m") as month')
            ->whereHas('shop', function ($query) use ($shopId) {
                $query->where('shops.id', $shopId);
            })
            ->where('date', '>=', $salesDateFrom)
            ->where('date', '<=', $salesDateTo)
            ->groupBy('month')
            ->get();

        $countsByMonth = [];
        foreach (CarbonPeriod::create($salesDateFrom, $salesDateTo)->months() as $month) {
            $countsByMonth[$month->format('Y-m')] = 0;
        }

        foreach ($relations as $relation) {
            /** @phpstan-ignore-next-line  */
            $countsByMonth[$relation->month] = $relation->count;
        }

        return $countsByMonth;
    }
}
