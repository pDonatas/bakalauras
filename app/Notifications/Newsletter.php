<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Newsletter extends Notification
{
    use Queueable;

    public function __construct(private readonly \App\Models\Newsletter $newsletter)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject($this->newsletter->title)
                    ->view('emails.newsletter', ['text' => $this->newsletter->text]);
    }

    /**
     * @param object $notifiable
     * @return array<string, string>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->newsletter->title,
            'text' => $this->newsletter->text,
        ];
    }
}
