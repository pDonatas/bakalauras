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

    public function testAdminCanViewPageIndex()
    {
        $shop = Shop::factory()->create();
        $page = Page::factory()->create(['shop_id' => $shop->id]);

        $response = $this->get(route('admin.pages.index', $shop->id));

        $response->assertStatus(200);
        $response->assertSee($page->title);
    }

    public function testAdminCanCreatePage()
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

    public function testAdminCanViewEditPage()
    {
        $shop = Shop::factory()->create();
        $page = Page::factory()->create(['shop_id' => $shop->id]);

        $response = $this->get(route('admin.pages.edit', [$shop->id, $page->id]));

        $response->assertStatus(200);
        $response->assertSee($page->title);
    }

    public function testAdminCanUpdatePage()
    {
        $shop = Shop::factory()->create();
        $page = Page::factory()->create(['shop_id' => $shop->id]);
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

    public function testAdminCanDeletePage()
    {
        $shop = Shop::factory()->create();
        $page = Page::factory()->create(['shop_id' => $shop->id]);

        $response = $this->delete(route('admin.pages.destroy', [$shop->id, $page->id]));

        $response->assertStatus(302);
        $this->assertDatabaseMissing('pages', [
            'id' => $page->id,
            'shop_id' => $shop->id,
        ]);
    }
}
