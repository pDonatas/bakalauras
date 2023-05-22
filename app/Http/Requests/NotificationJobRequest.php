<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class NotificationJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()?->isGranted(User::ROLE_ADMIN) || auth()->user()?->isGranted(User::ROLE_BARBER);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'notify_every' => ['required', 'integer', 'min:0'],
            'notify_period' => ['required', 'integer', 'min:0'],
        ];
    }
}
