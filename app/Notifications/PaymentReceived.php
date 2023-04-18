<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Order;
use App\Services\SMS\Channels\TwilioChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly Order $order
    ) {}

    /**
     * @return string[]
     */
    public function via(object $notifiable): array
    {
        return ['mail', TwilioChannel::class];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject(trans('Payment received for order #:orderNumber', [
                'orderNumber' => $this->order->id,
            ]))
            ->line(trans('Your payment for order #:orderNumber has been received.', [
                'orderNumber' => $this->order->id,
            ]))
            ->line(trans('Service date: :date', [
                'date' => $this->order->date . ' ' . $this->order->time,
            ]))
            ->line(trans('Order total: :price EUR', [
                'price' => $this->order->service->price,
            ]))
            ->line(trans('Service name: :serviceName', [
                'serviceName' => $this->order->service->name,
            ]))
            ->action(trans('View Order'), url("/user/orders/{$this->order->id}"));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'amount' => $this->order->service->price,
            'service' => $this->order->service->name,
        ];
    }

    public function toTwilio(object $notifiable): string
    {
        return trans('Your payment for order #:orderId (:serviceName) has been received. Date: :date', [
            'orderId' => $this->order->id,
            'serviceName' => $this->order->service->name,
            'date' => $this->order->date . ' ' . $this->order->time,
        ]);
    }
}
