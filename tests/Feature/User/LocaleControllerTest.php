<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LocaleControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testLocaleCanBeChanged(): void
    {
        $response = $this->post(route('locale.index', ['locale' => 'fr']));

        $response->assertRedirect();
        $response->assertSessionHas('locale', 'fr');
        $this->assertEquals('fr', app()->getLocale());
    }
}
