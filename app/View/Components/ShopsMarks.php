<?php

namespace App\View\Components;

use App\Models\Shop;
use Illuminate\View\Component;
use Illuminate\View\View;

class ShopsMarks extends Component
{
    public function __construct(protected readonly Shop $shop)
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render(): View
    {
        $marks = $this->shop->marks;

        return view('components.shops-marks', compact('marks'));
    }
}
