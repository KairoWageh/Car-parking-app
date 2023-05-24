<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user can log in with correct credentials
     */
    public function testUserCanLoginWithCorrectCredentials()
    {
        $user = User::factory()->create();
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * Test user can not log in with incorrect credentials
     */

    public function testUserCannotLoginWithIncorrectCredentials()
    {
        $user = User::factory()->create();
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'wrong_password'
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test user can register with correct credentials
     */
    public function testUserCanRegisterWithCorrectCredentials()
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'user name',
            'email' => 'useremail@mail.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'access_token'
            ]);
        $this->assertDatabaseHas('users', [
            'name' => 'user name',
            'email' => 'useremail@mail.com'
        ]);

    }

    /**
     * Test user can not register with incorrect credentials
     */

    public function testUserCannotRegisterWithIncorrectCredentials()
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'new user name',
            'email' => 'newuseremail@email.com',
            'password' => 'password',
            'password_confirmation' => 'wrong_password'
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertDatabaseMissing('users', [
            'name' => 'new user name',
            'email' => 'newuseremail@email.com',
        ]);
    }
}
