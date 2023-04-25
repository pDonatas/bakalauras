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
        $shops = auth()->user()->relatedShops()->paginate();
        if (auth()->user()->isAdmin()) {
            $shops = Shop::paginate();
        }

        $users = User::where('role', '>=', User::ROLE_BARBER)->get();

        return view('admin.shops.index', compact('shops', 'users'));
    }

    public function store(CreateShopRequest $request): RedirectResponse
    {
        $shop = Shop::create($request->validated());

        $file = $request->file('photo');

        $name = $file->getClientOriginalName();

        $newName = time() . '_' . $name;

        $newPath = 'images/shops/' . $shop->id;

        \Storage::disk('public')->putFileAs($newPath, $file, $newName);

        $shop->update([
            'photo' => '/storage/images/shops/' . $shop->id . '/' . $newName,
        ]);

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
        if ($request->file('photo')) {
            $file = $request->file('photo');

            $name = $file->getClientOriginalName();

            $newName = time() . '_' . $name;

            $newPath = 'images/shops/' . $shop->id;

            \Storage::disk('public')->putFileAs($newPath, $file, $newName);

            $shop->update([
                'photo' => '/storage/images/shops/' . $shop->id . '/' . $newName,
            ]);
        }

        $request = $request->validated();
        $workers = $request['workers'] ?? [];
        unset($request['photo']);
        unset($request['workers']);

        $shop->update($request);
        $shop->workers()->sync($workers);

        return redirect()->route('admin.shops.show', $shop);
    }

    public function destroy(Shop $shop): RedirectResponse
    {
        $shop->delete();

        return redirect()->route('admin.shops.index');
    }
}
