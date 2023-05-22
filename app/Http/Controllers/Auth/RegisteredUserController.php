<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(UserRegisterRequest $request): RedirectResponse
    {
        $role = isset($request->isBarber) ? User::ROLE_BARBER : User::ROLE_USER;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
        ]);

        if (User::ROLE_BARBER === $role) {
            $shop = $user->ownedShops()->create([
                'company_name' => $request->company_name,
                'company_code' => $request->company_code,
                'company_address' => $request->company_address,
                'company_phone' => $request->company_phone,
            ]);

            $user->shops()->attach($shop);
            $shop->workers()->attach($user);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
