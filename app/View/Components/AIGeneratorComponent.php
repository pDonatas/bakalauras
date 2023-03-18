<?php

namespace App\View\Components;

use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AIGeneratorComponent extends Component
{
    public function __construct(
        private readonly Service $service
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        // Build form fields based on service
        $category = $this->service->category->id;
        $form = match ($category) {
            1, 7, 8, 9 => 'components.forms.ai.hair',
            2 => 'components.forms.ai.nails',
            default => 'empty',
        };

        return view($form, [
            'category' => $category,
        ]);
    }
}
