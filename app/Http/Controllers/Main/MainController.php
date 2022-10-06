<?php

declare(strict_types=1);

namespace App\Http\Controllers\Main;

use App\Models\Shop;
use Illuminate\View\View;

class MainController
{
    public function index(): View
    {
        $shops = Shop::paginate();

        return view('index', compact('shops'));
    }
}
