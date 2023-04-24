<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Controllers\User;

use App\Http\Controllers\User\UserController;
use App\Http\Requests\UserEditRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function profileReturnsViewWithUserData(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('user.profile'));

        $response->assertOk();
        $response->assertViewIs('user.profile');
        $response->assertViewHas('user', $user->loadCount('orders', 'ownedShops', 'marks'));
    }

    /** @test */
    public function ordersReturnsViewWithPaginatedOrders(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('user.orders'));

        $response->assertOk();
        $response->assertViewIs('user.orders.list');
    }

    /** @test */
    public function editReturnsViewWithUserData(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('profile.edit'));

        $response->assertOk();
        $response->assertViewIs('user.edit');
        $response->assertViewHas('user', $user);
    }

    /** @test */
    public function updateUpdatesUserData(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $this->actingAs($user);

        $newData = User::factory()->make()->toArray();
        $newData['avatar'] = new UploadedFile($this->faker->image(), 'avatar.jpg', 'image/jpeg', null, true);

        $response = $this->put(route('profile.update'), $newData);

        $response->assertRedirect(route('user.profile'));
        $this->assertDatabaseHas('users', ['email' => $newData['email']]);
    }

    /** @test */
    public function updateDoesNotUpdateUserDataWithInvalidInput(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $newData = [
            'name' => '',
            'email' => 'invalidemail',
            'avatar' => 'not_a_file',
        ];

        $response = $this->put(route('profile.update'), $newData);

        $response->assertSessionHasErrors(['name', 'email', 'avatar']);
        $this->assertDatabaseMissing('users', ['email' => $newData['email']]);
    }
}
