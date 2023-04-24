<?php

namespace Tests\Feature\Admin;

use App\Models\Photo;
use App\Models\Service;
use App\Models\Shop;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Tests\TestCaseExtended;

class PhotoControllerTest extends TestCase
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

    public function testAdminCanViewPhotoIndex(): void
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create();
        $service = Service::factory()->create(['shop_id' => $shop->id, 'user_id' => $user->id]);
        $photo = Photo::factory()->create(['service_id' => $service->id]);

        $response = $this->get(route('admin.photos.index', [$shop->id, $service->id]));

        $response->assertStatus(200);
        $response->assertSee($photo->path);
    }

    public function testAdminCanViewCreatePhotoForm(): void
    {
        $shop = Shop::factory()->create();
        $user = User::factory()->create();
        $service = Service::factory()->create(['shop_id' => $shop->id, 'user_id' => $user->id]);

        $response = $this->get(route('admin.photos.create', [$shop->id, $service->id]));

        $response->assertStatus(200);
    }

    public function testAdminCanCreatePhoto(): void
    {
        $shop = Shop::factory()->create();
        $user = User::factory()->create();
        $service = Service::factory()->create(['shop_id' => $shop->id, 'user_id' => $user->id]);
        $image = $this->faker->image();

        $response = $this->post(route('admin.photos.store', [$shop->id, $service->id]), [
            'file' => new UploadedFile($image, "test.png", null, null, true),
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('photos', [
            'service_id' => $service->id,
            'path' => $response->original['path'],
        ]);
        \Storage::disk('public')->delete($response->original['path']);
    }

    public function testAdminCanViewPhoto(): void
    {
        $shop = Shop::factory()->create();
        $user = User::factory()->create();
        $service = Service::factory()->create(['shop_id' => $shop->id, 'user_id' => $user->id]);
        $photo = Photo::factory()->create([
            'service_id' => $service->id,
        ]);

        $response = $this->get(route('admin.photos.show', [$shop->id, $service->id, $photo->id]));

        $response->assertStatus(200);
        $response->assertSee($photo->path);
    }

    public function testAdminCanViewEditPhotoForm(): void
    {
        $shop = Shop::factory()->create();
        $user = User::factory()->create();
        $service = Service::factory()->create(['shop_id' => $shop->id, 'user_id' => $user->id]);
        $photo = Photo::factory()->create([
            'service_id' => $service->id,
        ]);

        $response = $this->get(route('admin.photos.edit', [$shop->id, $service->id, $photo->id]));

        $response->assertStatus(200);
        $response->assertSee($photo->path);
    }

    public function testAdminCanUpdatePhoto(): void
    {
        $shop = Shop::factory()->create();
        $user = User::factory()->create();
        $service = Service::factory()->create(['shop_id' => $shop->id, 'user_id' => $user->id]);

        $photo = Photo::factory()->create([
            'service_id' => $service->id,
        ]);

        $response = $this->put(route('admin.photos.update', [$shop->id, $service->id, $photo->id]), [
            'path' => $this->faker->image,
        ]);

        $response->assertStatus(302);
    }

    public function testAdminCanDeletePhoto(): void
    {
        $shop = Shop::factory()->create();
        $user = User::factory()->create();
        $service = Service::factory()->create(['shop_id' => $shop->id, 'user_id' => $user->id]);
        $photo = Photo::factory()->create([
            'service_id' => $service->id,
        ]);

        $response = $this->delete(route('admin.photos.destroy', [$shop->id, $service->id, $photo->id]));

        $response->assertStatus(302);
        $this->assertModelMissing($photo);
    }
}
