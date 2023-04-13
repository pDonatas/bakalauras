<?php

declare(strict_types=1);

namespace App\Services\SMS\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Api\V2010\Account\MessageInstance;
use Twilio\Rest\Client;

readonly class TwilioChannel
{
    public function __construct(
        private Client $twilio
    ) {}

    /**
     * @throws TwilioException
     */
    public function send(object $notifiable, Notification $notification): ?MessageInstance
    {
        $to = $this->getTo($notifiable);
        if (! method_exists($notification, 'toTwilio')) {
            throw new \InvalidArgumentException('Notification does not have a toTwilio method');
        }

        $message = $notification->toTwilio($notifiable);

        try {
            return $this->twilio->messages->create(
                $to,
                [
                    'from' => config('sms.from'),
                    'body' => $message,
                ]
            );
        } catch (TwilioException $e) {
            Log::error('SMS: ' . $e->getMessage());

            throw $e;
        }
    }

    protected function getTo(object $notifiable): string
    {
        if (isset($notifiable->phone_number)) {
            return $notifiable->phone_number;
        }

        throw new \InvalidArgumentException('Phone number not provided');
    }
}
