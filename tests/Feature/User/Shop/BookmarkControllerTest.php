<?php

declare(strict_types=1);

namespace Tests\Feature\User\Shop;

use App\Models\Bookmark;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookmarkControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testCreate(): void
    {
        $this->actingAsUser();

        $shop = Shop::factory()->create();

        $response = $this->get(route('bookmark.create', $shop));

        $response->assertRedirect();

        $this->assertDatabaseHas('bookmarks', [
            'user_id' => auth()->id(),
            'shop_id' => $shop->id,
        ]);
    }

    public function testDestroy(): void
    {
        $this->actingAsUser();
        $user = User::factory()->create();
        $shop = Shop::factory()->create([
            'owner_id' => $user->id,
        ]);

        Bookmark::factory()->create(['user_id' => $user->id, 'shop_id' => $shop->id]);

        $response = $this->get(route('bookmark.destroy', $shop->id));

        $response->assertRedirect();

        $this->assertDatabaseMissing('bookmarks', [
            'user_id' => auth()->id(),
            'shop_id' => $shop->id,
        ]);
    }

    private function actingAsUser(): void
    {
        $user = $this->createUser();

        $this->actingAs($user);
    }

    private function createUser(): object
    {
        return User::factory()->create();
    }
}
