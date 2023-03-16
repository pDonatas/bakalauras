<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\Service\CreateServiceRequest;
use App\Http\Requests\Shop\Service\UpdateServiceRequest;
use App\Models\Category;
use App\Models\Service;
use App\Models\Shop;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(Shop $shop): View
    {
        if (auth()->id() == $shop->owner_id || auth()->user()->isAdmin()) {
            $services = $shop->services()->paginate();
        } else {
            $services = $shop->services()->where('user_id', auth()->id())->paginate();
        }

        return view('admin.shops.services.index', compact('shop', 'services'));
    }

    public function store(Shop $shop, CreateServiceRequest $request): RedirectResponse
    {
        $service = $shop->services()->create($request->validated());

        return redirect()->route('admin.services.edit', compact('shop', 'service'))->with('success', __('Service created successfully'));
    }

    public function edit(Shop $shop, Service $service): View
    {
        $categories = Category::all();

        return view('admin.shops.services.edit', compact('shop', 'service', 'categories'));
    }

    public function show(Shop $shop, Service $service): RedirectResponse
    {
        return redirect()->route('admin.services.edit', compact('shop', 'service'));
    }

    public function update(UpdateServiceRequest $request, Shop $shop, Service $service): RedirectResponse
    {
        $service->update($request->validated());

        return redirect()->route('admin.services.edit', [$shop, $service])->with('success', __('Service updated successfully'));
    }

    public function destroy(Shop $shop, Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()->route('admin.services.index', $service->id)->with('success', __('Service removed successfully'));
    }
}
