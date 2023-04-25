<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Order;
use App\Services\SMS\Channels\TwilioChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TimeChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly Order $order,
        private readonly string $oldDate,
        private readonly string $oldTime,
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
            ->subject(trans('Time changed for order #:orderNumber', [
                'orderNumber' => $this->order->id,
            ]))
            ->line(trans('Your order #:orderNumber time has been changed.', [
                'orderNumber' => $this->order->id,
            ]))
            ->line(trans('Old date: :date', [
                'date' => $this->oldDate . ' ' . $this->oldTime,
            ]))
            ->line(trans('New date: :date', [
                'date' => $this->order->date . ' ' . $this->order->time,
            ]))
            ->line(trans('Service name: :serviceName', [
                'serviceName' => $this->order->service->name,
            ]))
            ->action(trans('View Order'), url("/orders/{$this->order->id}"));
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
        return trans('Your order #:orderId (:serviceName) time has been changed. Date: :date', [
            'orderId' => $this->order->id,
            'serviceName' => $this->order->service->name,
            'date' => $this->order->date . ' ' . $this->order->time,
        ]);
    }
}
