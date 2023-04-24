<?php

namespace Tests\Feature\Admin;

use App\Models\Page;
use App\Models\Shop;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PageControllerTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(CategorySeeder::class);

        $user = User::factory()->create([
            'role' => User::ROLE_ADMIN
        ]);

        $this->actingAs($user);
    }

    public function testAdminCanViewPageIndex(): void
    {
        $shop = Shop::factory()->create();
        $page = Page::factory()->create(['shop_id' => $shop->id]);

        $response = $this->get(route('admin.pages.index', $shop->id));

        $response->assertStatus(200);
        assert(isset($page->title));
        $response->assertSee($page->title);
    }

    public function testAdminCanCreatePage(): void
    {
        $shop = Shop::factory()->create();
        $name = $this->faker->title;
        $description = $this->faker->paragraph;

        $response = $this->post(route('admin.pages.store', $shop->id), [
            'name' => $name,
            'description' => $description,
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('pages', [
            'shop_id' => $shop->id,
            'name' => $name,
            'description' => $description,
        ]);
    }

    public function testAdminCanViewEditPage(): void
    {
        $shop = Shop::factory()->create();

        assert($shop instanceof Shop);

        $page = Page::factory()->create(['shop_id' => $shop->id]);
        assert($page instanceof Page);

        $response = $this->get(route('admin.pages.edit', [$shop->id, $page->id]));

        $response->assertStatus(200);
        $response->assertSee($page->title);
    }

    public function testAdminCanUpdatePage(): void
    {
        $shop = Shop::factory()->create();

        assert($shop instanceof Shop);

        $page = Page::factory()->create(['shop_id' => $shop->id]);

        assert($page instanceof Page);

        $newName = $this->faker->title;
        $newDescription = $this->faker->paragraph;

        $response = $this->put(route('admin.pages.update', [$shop->id, $page->id]), [
            'name' => $newName,
            'description' => $newDescription,
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'shop_id' => $shop->id,
            'name' => $newName,
            'description' => $newDescription,
        ]);
    }

    public function testAdminCanDeletePage(): void
    {
        $shop = Shop::factory()->create();

        assert($shop instanceof Shop);

        $page = Page::factory()->create(['shop_id' => $shop->id]);

        assert($page instanceof Page);

        $response = $this->delete(route('admin.pages.destroy', [$shop->id, $page->id]));

        $response->assertStatus(302);
        $this->assertDatabaseMissing('pages', [
            'id' => $page->id,
            'shop_id' => $shop->id,
        ]);
    }
}
