<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserCRUDTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_register_a_user(): void
    {
        $response = $this->postJson('/api/register', [
            'first_name' => 'Test',
            'last_name'  => 'Test',
            'phone'      => '123456789',
            'login'      => 'Test',
            'password'   => 'Test1234!',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => ['id', 'first_name', 'last_name', 'login'],
                 ]);

        $this->assertDatabaseHas('users', [
            'login' => 'Test',
        ]);
    }

    /** @test */
    public function it_requires_authentication_to_access_users(): void
    {
        $response = $this->getJson('/api/users');

        $response->assertStatus(401)
                 ->assertJson([
                     'message' => 'Unauthenticated.',
                 ]);
    }

    /** @test */
    public function it_can_list_users_with_sanctum_auth(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('Test1234!'),
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data',
                 ]);
    }

    /** @test */
    public function it_can_update_a_user(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('Test1234!'),
        ]);

        Sanctum::actingAs($user);

        $response = $this->putJson("/api/users/{$user->id}", [
            'first_name' => 'Test1',
            'last_name'  => 'Test1',
            'phone'      => '987654321',
            'login'      => 'Test1',
            'password'   => 'Test1234!!',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status'  => 'success',
                     'message' => 'User updated successfully',
                 ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'first_name' => 'Test1',
        ]);
    }

    /** @test */
    public function it_can_delete_a_user(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}
