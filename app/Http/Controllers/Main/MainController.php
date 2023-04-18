<?php

declare(strict_types=1);

namespace App\Http\Controllers\Main;

use App\Enums\City;
use App\Models\Category;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MainController
{
    public function index(Request $request): View
    {
        $searchParams = $request->only(['category_id', 'company_name', 'name', 'city', 'price_from', 'price_to']);
        $shops = Shop::when(isset($searchParams['category_id']), fn ($query) => $query->with('services')->whereRelation('services', 'category_id', $searchParams['category_id']))
            ->when(isset($searchParams['company_name']), fn ($query) => $query->where('company_name', 'like', "%{$searchParams['company_name']}%"))
            ->when(isset($searchParams['name']), fn ($query) => $query->where('name', 'like', "%{$searchParams['name']}%"))
            ->when(isset($searchParams['city']), fn ($query) => $query->where('company_address', 'like', '%' . $searchParams['city'] . '%'))
            ->when(isset($searchParams['price_from']), fn ($query) => $query->with('services')->whereRelation('services', 'price', '>=', $searchParams['price_from']))
            ->when(isset($searchParams['price_to']), fn ($query) => $query->with('services')->whereRelation('services', 'price', '<=', $searchParams['price_to']))
            ->get();
        $cities = City::cases();
        $categories = Category::all();
        $favorites = auth()->user()->bookmarks ?? [];

        if ($favorites) {
            $favorites = $favorites->pluck('shop_id')->toArray();
            $favorites = $shops->filter(fn ($shop) => in_array($shop->id, $favorites));
        }

        return view('index', compact('shops', 'categories', 'cities', 'favorites'));
    }
}
