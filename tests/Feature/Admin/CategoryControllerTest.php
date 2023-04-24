<?php

namespace Tests\Feature\Admin;

use App\Http\Requests\CreateCategoryRequest;
use App\Models\Category;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;
use Tests\TestCaseExtended;

class CategoryControllerTest extends TestCase
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

    public function testIndex(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $this->actingAs($admin);

        Category::factory()->count(12)->create();

        $response = $this->get(route('admin.categories.index'));

        $response->assertOk();
        $response->assertViewIs('admin.categories.index');
        $response->assertViewHas('categories');
        $this->assertInstanceOf(LengthAwarePaginator::class, $response->viewData('categories'));
    }

    public function testEdit(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $this->actingAs($admin);

        $category = Category::factory()->create();

        $response = $this->get(route('admin.categories.edit', ['category' => $category]));

        $response->assertOk();
        $response->assertViewIs('admin.categories.edit');
        $response->assertViewHas('category');
        $this->assertInstanceOf(Category::class, $response->viewData('category'));
    }

    public function testStore(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $this->actingAs($admin);

        $category = Category::factory()->create();

        $data = [
            'name' => $category->name,
        ];

        $request = CreateCategoryRequest::create(route('admin.categories.store'), 'POST', $data);
        $response = Route::dispatch($request);

        $response->isRedirect(route('admin.categories.index'));

        $this->assertDatabaseHas('categories', $data);
    }

    public function testUpdate(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $this->actingAs($admin);

        $category = Category::factory()->create();

        $data = [
            'name' => 'New Name',
        ];

        $updated = $category->toArray();
        $updated['name'] = $data['name'];

        $response = $this->put(route('admin.categories.update', $category->id), $updated);

        $response->isRedirect(route('admin.categories.index'));

        $this->assertDatabaseHas('categories', $data);
    }

    public function testDestroy(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $this->actingAs($admin);

        $category = Category::factory()->create();

        $response = $this->delete(route('admin.categories.destroy', ['category' => $category]));

        $response->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
