<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCallendarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'work_days' => ['required', 'array'],
            'work_days.*' => ['required', 'string', 'in:1,2,3,4,5,6,7'],
            'from' => ['required', 'string', 'date_format:H:i'],
            'to' => ['required', 'string', 'date_format:H:i'],
        ];
    }
}
