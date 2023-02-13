<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\CreateShopRequest;
use App\Http\Requests\Shop\UpdateShopRequest;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(): View
    {
        $shops = Shop::paginate();
        $users = User::where('role', User::ROLE_BARBER)->get();

        return view('admin.shops.index', compact('shops', 'users'));
    }

    public function store(CreateShopRequest $request): RedirectResponse
    {
        $shop = Shop::create($request->validated());

        $shop->save();

        return redirect()->route('admin.shops.index')->with('success', __('Shop created successfully'));
    }

    public function show(Shop $shop): View
    {
        return view('admin.shops.show', compact('shop'));
    }

    public function edit(Shop $shop): View
    {
        $users = User::where('role', User::ROLE_BARBER)->get();

        return view('admin.shops.edit', compact('shop', 'users'));
    }

    public function update(UpdateShopRequest $request, Shop $shop): RedirectResponse
    {
        $shop->update($request->validated());

        return redirect()->route('admin.shops.show', $shop);
    }

    public function destroy(Shop $shop): RedirectResponse
    {
        $shop->delete();

        return redirect()->route('admin.shops.index');
    }
}
