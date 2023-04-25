<?php

declare(strict_types=1);

namespace App\Http\Requests\Shop;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateShopRequest extends FormRequest
{
    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'company_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'owner_id' => 'required|exists:users,id',
            'workers' => 'nullable|array',
            'photo' => 'nullable|sometimes|image',
            'company_address' => 'sometimes|string|max:255',
            'company_phone' => 'sometimes|string|max:255',
            'company_code' => 'sometimes|string|max:255',
        ];
    }

    public function authorize(): bool
    {
        return auth()->check() && $this->owner_id == auth()->id() || auth()->user()->isGranted(User::ROLE_ADMIN);
    }
}
