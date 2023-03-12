<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function show(int $id): View
    {
        $shop = Shop::withCount('marks')->find($id);

        return view('user.shops.show', compact('shop'));
    }
}
