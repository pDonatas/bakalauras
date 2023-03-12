<?php

namespace App\View\Components\Shops;

use App\Models\Shop;
use Illuminate\View\Component;
use Illuminate\View\View;

class Services extends Component
{
    public function __construct(protected readonly Shop $shop)
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View
     */
    public function render(): View
    {
        $services = $this->shop->services;

        return view('components.shops.services', compact('services'));
    }
}
