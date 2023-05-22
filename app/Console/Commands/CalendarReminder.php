<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class CalendarReminder extends Command
{
    protected $signature = 'app:calendar-reminder';

    protected $description = 'Barber set calendar reminder handler';

    public function handle(): void
    {
        $barbers = User::where('role', User::ROLE_BARBER)
            ->orWhere('role', User::ROLE_ADMIN)
            ->with('notificationJob')
            ->get();

        foreach ($barbers as $barber) {
            if (!$barber->notificationJob) {
                continue;
            }

            if ($barber->notificationJob->next_run > now()->timestamp) {
                continue;
            }

            $this->sendCalendarReminder($barber);
            $this->calculateNextReminder($barber);
        }
    }

    private function sendCalendarReminder(User $barber): void
    {
        Order::providedActiveOrders($barber->id)->chunk(100, function (Collection $orders) use ($barber) {
            foreach ($orders as $order) {
                echo "Send calendar reminder for order {$order->id}" . PHP_EOL;
                $barber->notify(
                    new \App\Notifications\CalendarReminder($order)
                );

                $order->user->notify(
                    new \App\Notifications\CalendarReminder($order)
                );
            }
        });
    }

    private function calculateNextReminder(User $barber): void
    {
        $job = $barber->notificationJob;
        $interval = $job->notify_every;
        $period = match ($job->notify_period) {
            0 => 'minutes',
            1 => 'hours',
            2 => 'days',
        };

        $barber->notificationJob->next_run = now()->add($interval, $period)->timestamp;
        $barber->notificationJob->save();
    }
}
