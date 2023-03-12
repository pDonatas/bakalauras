<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Services\DashboardService;
use Illuminate\View\View;

class AdminController
{
    public function __construct(private readonly DashboardService $dashboardService) {
    }

    public function index(): View
    {
        $shopsCount = 0;
        $averageRating = 0;
        $ordersCount = 0;
        $uniqueClientsCount = 0;

        return view('admin.dashboard', [
            'shopsCount' => $shopsCount,
            'averageRating' => $averageRating,
            'ordersCount' => $ordersCount,
            'uniqueClientsCount' => $uniqueClientsCount,
        ]);
    }
}
