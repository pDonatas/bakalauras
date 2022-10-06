<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\CreateShopRequest;
use App\Http\Requests\Shop\UpdateShopRequest;
use App\Models\Shop;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function create(): View
    {
        return view('shop.create');
    }

    public function store(CreateShopRequest $request): RedirectResponse
    {
        $shop = Shop::create($request->validated());

        return redirect()->route('shop.show', $shop);
    }

    public function show(Shop $shop): View
    {
        return view('shop.show', compact('shop'));
    }

    public function edit(Shop $shop)
    {
        return view('shop.edit', compact('shop'));
    }

    public function update(UpdateShopRequest $request, Shop $shop): RedirectResponse
    {
        $shop->update($request->validated());

        return redirect()->route('shop.show', $shop);
    }

    public function destroy(Shop $shop): RedirectResponse
    {
        $shop->delete();

        return redirect()->route('shop.index');
    }
}
