<?php

declare(strict_types=1);

namespace App\Http\Requests\Shop;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class CreateShopRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }

    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isGranted(User::ROLE_BARBER);
    }
}
