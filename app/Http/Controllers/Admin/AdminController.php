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
        $cpu = $this->dashboardService->getCPUUsage();

        return view('admin.dashboard', compact('cpu'));
    }
}
