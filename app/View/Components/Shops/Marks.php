<?php

declare(strict_types=1);

namespace App\View\Components\Shops;

use App\Models\Shop;
use Illuminate\View\Component;
use Illuminate\View\View;

class Marks extends Component
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
        $marks = $this->shop->marks;

        return view('components.shops.marks', compact('marks'));
    }
}
