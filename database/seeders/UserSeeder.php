<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::factory(100)->make();

        // create shops for random users (50%)
        foreach ($users as $user) {
            $user->save();
            if (rand(0, 1)) {
                $user->role = User::ROLE_BARBER;
                $user->ownedShops()->create(Shop::factory()->raw());
                $user->save();
            }
        }
    }
}
