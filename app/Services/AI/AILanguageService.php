<?php

declare(strict_types=1);

namespace App\Services\AI;

use App\Models\Category;
use GuzzleHttp\Client;

class AILanguageService
{
    public function __construct(
        private readonly Client $client
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public function buildQuery(array $data): string
    {
        $category = $data['category_id'];
        $replaced = '';
        switch ($category) {
            case 2:
            case 11:
            case 12:
            case 13:
                $replaced = 'hair';
                break;
            case 5:
            case 6:
            case 14:
            case 15:
                $replaced = 'nails';
                break;
            default:
                break;
        }

        $query = 'A photo of a ';

        if ('' === $replaced) {
            $query .= $this->translate($data['description']);
        } else {
            $query .= $replaced;
            $query .= ' with ';

            unset($data['category_id']);
            unset($data['_token']);

            foreach ($data as $key => $value) {
                $key = str_replace(['_', $replaced], [' ', ''], $key);
                $query .= $key . ' ' . $value . ', ';
            }
        }

        return $query;
    }

    private function translate(string $data): string
    {
        $response = $this->client->request('POST', config('app.translator_url'), [
            'json' => [
                'input' => $data,
            ],
        ]);

        return $response->getBody()->getContents();
    }
}
