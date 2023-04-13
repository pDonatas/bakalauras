<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderPaid
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        private readonly Order $order
    ) {}

    public function getOrder(): Order
    {
        return $this->order;
    }

    /**
     * @return PrivateChannel[]
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('orders'),
        ];
    }
}
