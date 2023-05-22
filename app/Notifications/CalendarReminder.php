<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CalendarReminder extends Notification
{
    use Queueable;

    public function __construct(private readonly Order $order)
    {
    }

    /**
     * @param object $notifiable
     * @return string[]
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject(trans('Reminder of order #:orderNumber', [
                'orderNumber' => $this->order->id,
            ]))
            ->line(trans('Reminder of order #:orderNumber', [
                'orderNumber' => $this->order->id,
            ]))
            ->line(trans('Date: :date', [
                'date' => $this->order->date . ' ' . $this->order->time,
            ]))
            ->line(trans('Service name: :serviceName', [
                'serviceName' => $this->order->service->name,
            ]))
            ->action(trans('View Order'), url("/admin/orders/{$this->order->id}"));
    }

    /**
     * @param object $notifiable
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
}
