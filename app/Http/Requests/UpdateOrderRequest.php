<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isGranted(1);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'time' => ['required', 'date_format:H:i'],
            'comment' => ['nullable', 'string', 'max:255'],
            'order_type' => ['required', 'in:1,2'],
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'status' => ['required', 'in:0,1,2,3'],
        ];
    }
}
