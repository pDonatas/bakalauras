<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Shop;

use App\Http\Controllers\Controller;
use App\Http\Services\TimeCalculationService;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct(
        private readonly TimeCalculationService $timeCalculationService
    ) {}

    public function photos(Service $service): JsonResponse
    {
        $photos = $service->photos;

        return new JsonResponse($photos);
    }

    public function time(Request $request, Service $service): JsonResponse
    {
        $selectedDate = $request->get('date');
        $workDay = $service->worker->workDay;
        $workTimes = $workDay ? [$workDay->from, $workDay->to] : [];

        $orders = Order::where('date', $selectedDate)
            ->where('service_id', $service->id)
            ->get();

        $enabledTimes = $this->timeCalculationService->calculate($workTimes, $orders);

        return new JsonResponse($enabledTimes);
    }
}
