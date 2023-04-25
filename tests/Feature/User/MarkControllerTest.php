<?php

namespace Tests\Feature\User;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class MarkControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testStore(): void
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();

        $this->actingAs($user);

        $mark = $this->faker->numberBetween(1, 5);
        $comment = $this->faker->sentence;

        $response = $this->post(route('vote', $shop), [
            'mark' => $mark,
            'comment' => $comment,
        ]);

        $response->assertRedirect(route('shop.show', $shop));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('marks', [
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'mark' => $mark,
            'comment' => $comment,
        ]);
    }
}
