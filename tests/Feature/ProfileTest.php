<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user can get their profile
     */
    public function testUserCanGetTheirProfile()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->getJson('/api/v1/profile');
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['name', 'email'])
            ->assertJsonCount(3)
            ->assertJsonFragment(['name' => $user->name]);
    }

    /**
     * Test user can update name and email
     */

    public function testUserCanUpdateNameAndEmail()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->putJson('/api/v1/profile', [
            'name' => 'name update',
            'email' => 'emailupdate@mail.com'
        ]);

        $response->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonStructure(['name', 'email'])
            ->assertJsonCount(2)
            ->assertJsonFragment(['name' => 'name update']);
        $this->assertDatabaseHas('users', [
            'name' => 'name update',
            'email' => 'emailupdate@mail.com'
        ]);
    }

    public function testUserCanChangePassword()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->putJson('/api/v1/password', [
            'current_password' => 'password',
            'password' => '123456789',
            'password_confirmation' => '123456789'
        ]);

        $response->assertStatus(Response::HTTP_ACCEPTED);
    }
}
