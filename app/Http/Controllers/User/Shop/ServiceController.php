<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Shop;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{
    public function photos(Service $service): JsonResponse
    {
        $photos = $service->photos;

        return new JsonResponse($photos);
    }
}
