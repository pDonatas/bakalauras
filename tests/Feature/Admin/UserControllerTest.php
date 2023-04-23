<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testItShouldReturnTheUsersIndexViewForAuthenticatedUsers()
    {
        // Arrange
        $user = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);
        $this->actingAs($user);

        // Act
        $response = $this->get(route('admin.users.index'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('admin.users.index');
        $response->assertViewHas('users');
    }

    public function testItShouldRedirectUnauthenticatedUsersToTheLoginPage()
    {
        // Act
        $response = $this->get(route('admin.users.index'));

        // Assert
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function testItShouldCreateANewUser()
    {
        // Arrange
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $this->actingAs($admin);
        $userData = User::factory()->make()->toArray();
        $userData['password'] = 'password';
        $userData['password_confirmation'] = 'password';

        // Act
        $response = $this->post(route('admin.users.store'), $userData);

        // Assert
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['email' => $userData['email']]);
    }

    public function testItShouldShowAUser()
    {
        // Arrange
        $user = User::factory()->create();
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $this->actingAs($admin);

        // Act
        $response = $this->get(route('admin.users.show', $user));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('admin.users.show');
        $response->assertViewHas('user');
    }

    public function testItShouldReturnTheEditViewForAuthenticatedUsers()
    {
        // Arrange
        $user = User::factory()->create();
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $this->actingAs($admin);

        // Act
        $response = $this->get(route('admin.users.edit', $user));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('admin.users.edit');
        $response->assertViewHas('user');
    }

    public function testItShouldUpdateAUser()
    {
        // Arrange
        $user = User::factory()->create();
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $this->actingAs($admin);
        $newData = User::factory()->make()->toArray();

        // Act
        $response = $this->put(route('admin.users.update', $user), $newData);

        // Assert
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.users.show', $user));
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => $newData['name'], 'email' => $newData['email']]);
    }

    public function testItShouldUpdateAUsersPassword()
    {
        // Arrange
        $user = User::factory()->create();
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $this->actingAs($admin);
        $newPassword = 'new_password';
        $newData = ['password' => $newPassword, 'password_confirmation' => $newPassword, 'name' => $user->name, 'email' => $user->email];

        // Act
        $response = $this->put(route('admin.users.update', $user), $newData);

        // Assert
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.users.show', $user));
        $this->assertTrue(Hash::check($newPassword, $user->fresh()->password));
    }

    public function testItShouldDeleteAUser()
    {
        // Arrange
        $user = User::factory()->create();
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $this->actingAs($admin);

        // Act
        $response = $this->delete(route('admin.users.destroy', $user));

        // Assert
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
