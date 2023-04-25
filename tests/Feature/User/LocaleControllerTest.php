<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocaleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testLocaleCanBeChanged(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('locale', ['locale' => 'fr']));

        $response->assertRedirect();
        $response->assertSessionHas('locale', 'fr');
        $this->assertEquals('fr', app()->getLocale());
    }
}
