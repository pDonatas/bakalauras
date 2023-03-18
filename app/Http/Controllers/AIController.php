<?php

namespace App\Http\Controllers;

use App\Services\AI\AILanguageService;
use App\Services\Images\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AIController extends Controller
{
    public function __construct(
        private readonly AILanguageService $aiLanguageService,
        private readonly ImageService $imageService
    ) {}

    public function generateImage(Request $request): JsonResponse
    {
        $query = $this->aiLanguageService->buildQuery($request->all());
        $imageData = Cache::get($query, function () use ($query) {
            $endpoint = 'https://api.openai.com/v1/images/generations';
            $data = [
                'model' => 'image-alpha-001',
                'prompt' => $query,
                'num_images' => 6,
                'size' => '256x256',
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . config('app.ai.key'), // Replace with your OpenAI API key
            ]);
            $response = curl_exec($ch);
            $data = json_decode($response, true);

            if (! isset($data['data'])) {
                return [
                    'data' => [],
                ];
            }

            $data['data'] = $this->imageService->upload($data['data']);

            Cache::put($query, $data);

            return $data;
        });

        return new JsonResponse(['data' => $imageData['data']], 200);
    }
}
