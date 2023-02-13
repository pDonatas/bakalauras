<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMarkRequest;
use App\Models\Shop;
use Illuminate\Http\RedirectResponse;

class MarkController extends Controller
{
    public function store(StoreMarkRequest $request, Shop $shop): RedirectResponse
    {
        $shop->marks()->create([
            'user_id' => $request->user()->id,
            'mark' => $request->mark,
            'comment' => $request->comment,
        ]);

        return redirect()->route('shops.show', $shop)->with('success', 'Mark created successfully');
    }
}
