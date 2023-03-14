<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AIController extends Controller
{
    public function generateImage(Request $request) {
        $query = $request->search ?? 'A cat';
// Set up API parameters
        $endpoint = 'https://api.openai.com/v1/images/generations';
        $data = [
            'model' => 'image-alpha-001',
            'prompt' => $query,
            'num_images' => 5,
            'size' => '256x256',
        ];

// Call API and get response
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer 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' // Replace with your OpenAI API key
        ));
        $response = curl_exec($ch);
        $image_data = json_decode($response, true);

        if (!isset($image_data['data'][0]['url'])) {
            return view('ai', ['images' => []]);
        }

        return view('ai', ['images' => $image_data['data']]);
    }
}
