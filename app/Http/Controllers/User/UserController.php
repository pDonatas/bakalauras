<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

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
}
