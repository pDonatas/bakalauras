<?php

declare(strict_types=1);

namespace App\Services\Payments;

interface PaymentServiceInterface
{
    public function pay(int $orderId, float $amount): string;
}
