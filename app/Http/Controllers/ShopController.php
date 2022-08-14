<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Shop\CreateShopRequest;
use App\Http\Requests\Shop\UpdateShopRequest;
use App\Models\Shop;

class ShopController extends Controller
{
    public function create()
    {
        return view('shop.create');
    }

    public function store(CreateShopRequest $request)
    {
        $shop = Shop::create($request->validated());

        return redirect()->route('shop.show', $shop);
    }

    public function show(Shop $shop)
    {
        return view('shop.show', compact('shop'));
    }

    public function edit(Shop $shop)
    {
        return view('shop.edit', compact('shop'));
    }

    public function update(UpdateShopRequest $request, Shop $shop)
    {
        $shop->update($request->validated());

        return redirect()->route('shop.show', $shop);
    }

    public function destroy(Shop $shop)
    {
        $shop->delete();

        return redirect()->route('shop.index');
    }
}
