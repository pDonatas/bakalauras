<?php

declare(strict_types=1);

namespace App\Services\Payments;

class PayseraService implements PaymentServiceInterface
{
    public function pay(int $orderId, int $amount): string
    {
        $data = [
            'projectid' => config('services.paysera.project_id'),
            'sign_password' => config('services.paysera.sign_password'),
            'orderid' => $orderId,
            'amount' => $amount * 100,
            'currency' => 'EUR',
            'p_email' => auth()->user()->email,
            'accepturl' => route('orders.success'),
            'cancelurl' => route('orders.fail'),
            'callbackurl' => route('orders.callback'),
            'test' => 1,
            'lang' => 'lt',
        ];

        $data = base64_encode(http_build_query($data));
        $data = str_replace(['+', '/'], ['-', '_'], $data);

        $sign = md5($data . config('services.paysera.sign_password'));

        return 'https://www.paysera.com/pay/?data=' . $data . '&sign=' . $sign;
    }
}
