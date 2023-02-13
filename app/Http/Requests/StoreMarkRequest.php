<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMarkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && $this->user()->marks()->where('shop_id', $this->route('shop')->id)->doesntExist();
    }

    public function rules(): array
    {
        return [
            'mark' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ];
    }
}
