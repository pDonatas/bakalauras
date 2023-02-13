<?php

declare(strict_types=1);

namespace App\Http\Requests\Shop\Page;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class CreatePageRequest extends FormRequest
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
        return auth()->check() && auth()->id() == $this->shop->owner_id || auth()->user()->role == User::ROLE_ADMIN;
    }
}
