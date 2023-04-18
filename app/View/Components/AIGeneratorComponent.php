<?php

declare(strict_types=1);

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
            2, 11, 13, 12 => 'components.forms.ai.hair',
            5, 6, 14, 15 => 'components.forms.ai.nails',
            default => 'components.forms.ai.custom',
        };

        return view($form, [
            'category' => $category,
        ]);
    }
}
