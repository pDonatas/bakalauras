<?php

declare(strict_types=1);

namespace App\Http\Requests\Shop\Service;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && ($this->shop->owner_id === auth()->id() || $this->shop->workers->contains(auth()->id())) || auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'user_id' => ['required', 'exists:users,id'],
            'length' => ['required', 'numeric', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
        ];
    }
}
