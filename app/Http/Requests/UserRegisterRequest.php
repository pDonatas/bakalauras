<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class UserRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return !auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        if ($this->isBarber) {
            $additionalRules = [
                'company_name' => ['required', 'string', 'max:255'],
                'company_code' => ['required', 'string', 'max:255', 'unique:shops'],
                'company_address' => ['required', 'string', 'max:255'],
                'company_phone' => ['required', 'string', 'max:255'],
            ];

            $rules = array_merge_recursive($rules, $additionalRules);
        }

        return $rules;
    }
}
