<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Shop;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use App\Models\Shop;
use Illuminate\Http\RedirectResponse;

class BookmarkController extends Controller
{
    public function create(Shop $shop): RedirectResponse
    {
        Bookmark::create([
            'user_id' => auth()->id(),
            'shop_id' => $shop->id,
        ]);

        return redirect()->back();
    }

    public function destroy(Shop $shop): RedirectResponse
    {
        Bookmark::where('user_id', auth()->id())
            ->where('shop_id', $shop->id)
            ->delete();

        return redirect()->back();
    }
}
