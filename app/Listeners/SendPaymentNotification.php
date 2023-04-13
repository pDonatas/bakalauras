<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\OrderPaid;
use App\Notifications\PaymentReceived;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPaymentNotification implements ShouldQueue
{
    public function handle(OrderPaid $event): void
    {
        $order = $event->getOrder();
        $order->user->notify(new PaymentReceived($order));
    }
}
