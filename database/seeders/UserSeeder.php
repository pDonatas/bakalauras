<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Photo;
use App\Models\Service;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::factory(100)->make();

        foreach ($users as $user) {
            $user->save();
            if (rand(0, 1)) {
                $shop = Shop::factory()->make();
                $user->role = User::ROLE_BARBER;
                $user->save();
                $shop->owner_id = $user->id;
                $shop->save();
                /**
                 * @var Collection<int, Service> $services
                 */
                $services = $shop
                    ->services()
                    ->saveMany(
                        Service::factory(5)
                            ->make(
                                [
                                    'shop_id' => $shop->id,
                                    'user_id' => $user->id,
                                ]
                            )
                    );

                $services->each(function (Service $service) {
                    $service->photos()->saveMany(Photo::factory(5)->make([
                        'service_id' => $service->id,
                    ]));
                });
            }
        }
    }
}
