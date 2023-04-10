<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Mark;
use App\Models\Order;

class DashboardService
{
    /**
     * @return string[]|null
     */
    public function getServerLoadLinuxData(): ?array
    {
        if (is_readable('/proc/stat')) {
            $stats = @file_get_contents('/proc/stat');

            if (false !== $stats) {
                // Remove double spaces to make it easier to extract values with explode()
                $stats = preg_replace('/[[:blank:]]+/', ' ', $stats);

                // Separate lines
                $stats = str_replace(["\r\n", "\n\r", "\r"], "\n", $stats);
                $stats = explode("\n", $stats);

                // Separate values and find line for main CPU load
                foreach ($stats as $statLine) {
                    $statLineData = explode(' ', trim($statLine));

                    // Found!
                    if (
                        (count($statLineData) >= 5) &&
                        ('cpu' == $statLineData[0])
                    ) {
                        return [
                            $statLineData[1],
                            $statLineData[2],
                            $statLineData[3],
                            $statLineData[4],
                        ];
                    }
                }
            }
        }

        return null;
    }

    public function getAverageRating(?int $userId = null): float
    {
        if ($userId) {
            $averageRating = Mark::where('user_id', $userId)->avg('mark') ?? 0;
        } else {
            $averageRating = Mark::avg('mark') ?? 0;
        }

        return $averageRating;
    }

    public function getUniqueClientsCount(?int $userId = null): int
    {
        if ($userId) {
            $uniqueClientsCount = Order::with('provider')
                ->whereRelation('provider', 'users.id', $userId)
                ->distinct()
                ->count('user_id');
        } else {
            $uniqueClientsCount = Order::distinct()->count('user_id');
        }

        return $uniqueClientsCount;
    }
}
