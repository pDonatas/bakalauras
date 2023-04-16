<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserEditRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserController extends Controller
{
    public function profile(): View
    {
        $user = \Auth::user()->loadCount('orders', 'ownedShops', 'marks');

        return view('user.profile', ['user' => $user]);
    }

    public function orders(): View
    {
        $orders = auth()->user()->orders()->paginate(10);

        return view('user.orders.list', compact('orders'));
    }

    public function edit(): View
    {
        $user = \Auth::user();

        return view('user.edit', ['user' => $user]);
    }

    public function update(UserEditRequest $request): RedirectResponse
    {
        $user = \Auth::user();

        assert($user instanceof User);

        $user->update($request->validated());

        if ($request->hasFile('avatar')) {
            $fileName = Str::random(10) . '.' . $request->file('avatar')->extension();

            $file = Storage::disk('public')->putFileAs('avatars', $request->file('avatar'), $fileName);

            $user->avatar = '/storage/' . $file;

            $user->save();
        }

        return redirect()->route('user.profile');
    }
}
