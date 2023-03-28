<?php

declare(strict_types=1);

namespace App\Services\Images;

use Illuminate\Support\Str;

class ImageService
{
    public function upload(array $images): array
    {
        $names = [];

        foreach ($images as $image) {
            $name = base64_encode(Str::random(10) . time()) . '.jpg';
            copy($image['url'], public_path('images/ai/' . $name));

            $names[] = '/images/ai/' . $name;
        }

        return $names;
    }
}
