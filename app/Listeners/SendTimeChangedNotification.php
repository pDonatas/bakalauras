<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\OrderTimeChanged;
use App\Notifications\OrderTimeChangedNotification;
use App\Notifications\TimeChanged;

class SendTimeChangedNotification
{
    public function handle(OrderTimeChanged $event): void
    {
        $order = $event->getOrder();
        $oldTime = $event->getOldTime();
        $oldDate = $event->getOldDate();
        $order->user->notify(new TimeChanged($order, $oldDate, $oldTime));
        $order->provider->notify(new OrderTimeChangedNotification($order, $oldDate, $oldTime));
    }
}
