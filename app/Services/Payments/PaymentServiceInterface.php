<?php

declare(strict_types=1);

namespace App\Services\Payments;

use Illuminate\Http\RedirectResponse;

interface PaymentServiceInterface
{
    public function pay(int $orderId, int $amount): string;
}
