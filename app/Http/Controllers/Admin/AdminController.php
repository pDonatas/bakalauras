<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Services\ShopService;
use App\Models\Order;
use App\Models\Shop;
use App\Models\User;
use App\Services\DashboardService;
use Illuminate\View\View;

readonly class AdminController
{
    public function __construct(
        private DashboardService $dashboardService,
        private ShopService $shopService,
    ) {}

    public function index(): View
    {
        $salesDateFrom = now()->subMonths(12);
        $salesDateTo = now();
        $user = \Auth::user();
        assert($user instanceof User);
        if (User::ROLE_ADMIN !== $user->role) {
            $user = auth()->user()->loadCount('ownedShops', 'orders');
            $shopsCount = $user->owned_shops_count ?? 0;
            $ordersCount = $user->orders_count ?? 0;
            $averageRating = $this->dashboardService->getAverageRating($user->id);
            $uniqueClientsCount = $this->dashboardService->getUniqueClientsCount($user->id);
            $shops = $user->ownedShops->map(function ($shop) use ($salesDateFrom, $salesDateTo) {
                return [
                    'company_name' => $shop->company_name,
                    'sales' => $this->shopService->countSales($salesDateFrom, $salesDateTo, $shop->id),
                ];
            });
            $workers = $user->ownedShops->map(function ($shop) {
                return $shop->workers;
            })->flatten();
            $orders = $user->providedOrders;
        } else {
            $shopsCount = Shop::count();
            $averageRating = $this->dashboardService->getAverageRating();
            $ordersCount = Order::count();
            $uniqueClientsCount = $this->dashboardService->getUniqueClientsCount();
            $shops = Shop::withCount('orders')->get()->map(function ($shop) use ($salesDateFrom, $salesDateTo) {
                return [
                    'company_name' => $shop->company_name,
                    'sales' => $this->shopService->countSales($salesDateFrom, $salesDateTo, $shop->id),
                ];
            });
            $workers = User::where('role', User::ROLE_BARBER)->get();
            $orders = Order::all();
        }

        return view('admin.dashboard', [
            'shopsCount' => $shopsCount,
            'averageRating' => $averageRating,
            'ordersCount' => $ordersCount,
            'uniqueClientsCount' => $uniqueClientsCount,
            'salesDateFrom' => $salesDateFrom,
            'salesDateTo' => $salesDateTo,
            'shops' => $shops,
            'workers' => $workers,
            'orders' => $orders,
        ]);
    }
}
