<?php

declare(strict_types=1);

namespace App\Services\AI;

class AILanguageService
{
    public function buildQuery(array $data): string
    {
        $category = $data['category_id'];
        $replaced = '';
        switch ($category) {
            case 1:
                $query = 'A photo of hair with ';
                $replaced = 'hair';
                break;
            case 2:
                $query = 'A photo of nails with ';
                $replaced = 'nails';
                break;
            case 3:
                $query = 'A photo of a face with ';
                $replaced = 'face';
                break;
            case 7:
                $query = 'A photo of a man hair with ';
                $replaced = 'man hair';
                break;
            case 8:
                $query = 'A photo of a woman hair with ';
                $replaced = 'woman hair';
                break;
            case 9:
                $query = 'A photo of a child hair with ';
                $replaced = 'child hair';
                break;
            default:
                $query = 'A photo of a ';
                break;
        }

        unset($data['category_id']);
        unset($data['_token']);

        foreach ($data as $key => $value) {
            $key = str_replace(['_', $replaced], [' ', ''], $key);
            $query .= $key . ' ' . $value . ', ';
        }

        return $query;
    }
}
