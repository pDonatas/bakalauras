<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\Page\CreatePageRequest;
use App\Http\Requests\Shop\Page\UpdatePageRequest;
use App\Models\Page;
use App\Models\Shop;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(Shop $shop): View
    {
        $pages = $shop->pages()->paginate();
        return view('admin.shops.pages.index', compact('shop', 'pages'));
    }

    public function store(Shop $shop, CreatePageRequest $request): RedirectResponse
    {
        $page = $shop->pages()->create($request->validated());

        return redirect()->route('admin.pages.edit', compact('shop', 'page'))->with('success', __('Page created successfully'));
    }

    public function edit(Shop $shop, Page $page): View
    {
        return view('admin.shops.pages.edit', compact('shop', 'page'));
    }

    public function show(Shop $shop, Page $page): RedirectResponse
    {
        return redirect()->route('admin.pages.edit', compact('shop', 'page'));
    }

    public function update(UpdatePageRequest $request, Shop $shop, Page $page): RedirectResponse
    {
        $page->update($request->validated());

        return redirect()->route('admin.pages.edit', [$shop, $page])->with('success', __('Page updated successfully'));
    }

    public function destroy(Shop $shop, Page $page): RedirectResponse
    {
        $page->delete();

        return redirect()->route('admin.pages.index', $shop->id)->with('success', __('Page removed successfully'));
    }

}
