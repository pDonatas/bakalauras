<?php

declare(strict_types=1);

namespace Tests\Feature\User\Shop;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CompareControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    public function testIndexMethodReturnsView(): void
    {
        $response = $this->get(route('shops.compare.index'));

        $response->assertViewIs('user.shops.compare');
        $response->assertOk();
    }
    public function testCompareMethodReturnsResponse(): void
    {
        $owner = User::factory()->create();
        $shop1 = Shop::factory()->create([
            'owner_id' => $owner->id,
        ]);
        $owner = User::factory()->create();
        $shop2 = Shop::factory()->create([
            'owner_id' => $owner->id,
        ]);
        $ids = "[$shop1->id, $shop2->id]";

        $response = $this->post(route('shops.compare'), [
            'ids' => $ids,
        ]);

        $response->assertOk();
        $response->assertHeader('content-type', 'text/html; charset=UTF-8');
    }
}
