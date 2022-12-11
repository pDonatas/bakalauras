<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && (auth()->user()->isGranted(User::ROLE_ADMIN) || $this->user->is(auth()->user()));
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$this->user->id,
            'current_password' => 'sometimes|string|current_password|nullable',
            'password' => 'exclude_without:current_password|string|min:6|confirmed',
        ];
    }
}
