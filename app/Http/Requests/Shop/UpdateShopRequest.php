<?php

declare(strict_types=1);

namespace App\Http\Requests\Shop;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateShopRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'company_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'owner_id' => 'required|exists:users,id',
            'workers' => 'nullable|array'
        ];
    }

    public function authorize(): bool
    {
        return auth()->check() && $this->owner_id == auth()->id() || auth()->user()->isGranted(User::ROLE_ADMIN);
    }
}
