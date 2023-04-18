<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Services\AIService;
use App\Services\AI\AILanguageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AIController extends Controller
{
    public function __construct(
        private readonly AILanguageService $aiLanguageService,
        private readonly AIService $aiService
    ) {}

    public function generateImage(Request $request): JsonResponse
    {
        $query = $this->aiLanguageService->buildQuery($request->all());
        $imageData = Cache::get($query, function () use ($query) {
            $images = $this->aiService->generateImages($query, 6);

            if (empty($images)) {
                return [];
            }

            Cache::put($query, $images);

            return $images;
        });

        return new JsonResponse(['data' => $imageData], 200);
    }
}
