<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'time' => ['required', 'date_format:H:i'],
            'comment' => ['nullable', 'string', 'max:255'],
            'order_type' => ['required', 'in:1,2'],
            'ai_photo' => ['nullable', 'string'],
            'current_photo_file' => ['nullable', 'file', 'image', 'max:2048'],
            'current_photo' => ['nullable'],
        ];
    }
}
