<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Models\Photo;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

readonly class AIService
{
    public function __construct(
        private Client $client
    ) {}

    /**
     * @return string[]
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function generateImages(string $query, int $count): array
    {
        $needsSaving = false;
        $images = $this->checkInCache($query, $count);

        if (count($images) < $count) {
            $neededCount = $count - count($images);
            $images = $this->checkInDatabase($query, $neededCount);
        }

        if (count($images) < $count) {
            $needsSaving = true;
            $neededCount = $count - count($images);
            $images = $this->checkInDalle($query, $neededCount);
        }

        if (count($images) < $count) {
            $needsSaving = true;
            $neededCount = $count - count($images);
            $images = $this->checkInStableHorde($query, $neededCount);
        }

        if (count($images) < $count) {
            $needsSaving = true;
            $neededCount = $count - count($images);
            $images = $this->checkInEvoke($query, $neededCount);
        }

        if ($needsSaving) {
            $images = $this->saveImages($query, $images);
        }

        return $images;
    }

    /**
     * @return string[]
     */
    private function checkInCache(string $query, int $count): array
    {
        return Cache::get($query)?->limit($count)->get()->toArray() ?? [];
    }

    /**
     * @return string[]
     */
    private function checkInDatabase(string $query, int $count): array
    {
        return Photo::where('query', $query)->select('path')->limit($count)->get()->toArray();
    }

    /**
     * @return string[]
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function checkInStableHorde(string $query, int $count): array
    {
        $response = $this->client->post('https://stablehorde.net/api/v2/generate/async', [
            'headers' => [
                'apikey' => config('app.ai.horde_key'),
                'Client-Agent' => 'TestApp:1.0',
            ],
            'json' => [
                'prompt' => $query,
                'params' => [
                    'n' => $count,
                ],
            ],
        ]);

        $data = json_decode($response->getBody()->getContents());

        if (isset($data->id)) {
            $fulfilled = $this->checkIsHordeGenerationFulfilled($data->id);

            if ($fulfilled) {
                return $this->getHordeImages($data->id);
            }
        }

        return [];
    }

    private function checkIsHordeGenerationFulfilled(string $id): bool
    {
        $response = $this->client->get("https://stablehorde.net/api/v2/generate/check/$id", [
            'headers' => [
                'apikey' => config('app.ai.horde_key'),
                'Client-Agent' => 'TestApp:1.0',
            ],
        ]);

        $data = json_decode($response->getBody()->getContents());

        return $data->done;
    }

    /**
     * @return string[]
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getHordeImages(string $id): array
    {
        $response = $this->client->get("https://stablehorde.net/api/v2/generate/status/$id", [
            'headers' => [
                'apikey' => config('app.ai.horde_key'),
                'Client-Agent' => 'TestApp:1.0',
            ],
        ]);

        $data = json_decode($response->getBody()->getContents());

        $images = [];
        if (isset($data->generations)) {
            foreach ($data->generations as $generation) {
                $images[] = $generation->img;
            }
        }

        return $images;
    }

    /**
     * @return string[]
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function checkInEvoke(string $query, int $count): array
    {
        $images = [];
        for ($i = 0; $i < $count; $i++) {
            $response = $this->client->post('https://3z3qqfddkf.execute-api.us-east-1.amazonaws.com/prod', [
                'json' => [
                    'token' => config('app.ai.evoke_key'),
                    'prompt' => $query,
                    'width' => 128,
                    'height' => 128,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents());

            $images[] = $data->body;
        }

        return $images;
    }

    /**
     * @param string[] $images
     *
     * @return string[]
     */
    private function saveImages(string $query, array $images): array
    {
        foreach ($images as &$image) {
            $name = md5($image);
            \Storage::disk('public')->put("images/ai/$name.png", file_get_contents($image));
            $path = "/storage/images/ai/$name.png";

            Photo::create([
                'query' => $query,
                'image' => $path,
            ]);

            $image = $path;
        }

        return $images;
    }

    /**
     * @return string[]
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function checkInDalle(string $query, int $neededCount): array
    {
        $response = $this->client->post('https://api.openai.com/v1/images/generations', [
            'headers' => [
                'Authorization' => 'Bearer ' . config('app.ai.openai_key'),
            ],
            'json' => [
                'prompt' => $query,
                'n' => $neededCount,
                'size' => '256x256',
            ],
        ]);

        $data = json_decode($response->getBody()->getContents());

        $images = [];
        if (isset($data->images)) {
            foreach ($data->images as $image) {
                $images[] = $image->url;
            }
        }

        return $images;
    }
}
