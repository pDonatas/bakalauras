<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class UserController extends Controller
{
    public function profile()
    {
        return view('user.profile');
    }

    public function orders(): View
    {
        $orders = auth()->user()->orders()->paginate(10);

        return view('user.orders.list', compact('orders'));
    }
}
