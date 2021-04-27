<?php

namespace Tests\Feature\Feature\Api\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginControllerTest extends TestCase
{
    public $user;
    public $password;

    public function setUp() : void
    {
        parent::setUp();

        User::whereEmail('user@test.com')->delete();
        $this->password = 'password';

        $this->user = User::create([

            'name' => 'User',
            'email' => 'user@test.com',
            'phone' => '07089898989',
            'password' => bcrypt($this->password),
            'type' => 'user'
        ]);
    }

    /** @test */
    public function it_should_ensure_login_fails_with_invalid_credentials()
    {
        $response = $this->post('/api/auth/login', [])
        ->assertStatus(422)
        ->assertJsonStructure([
            'message',
            'errors'
        ]);

        $response = $this->post('/api/auth/login', ['email' => '', 'password' => ''])
        ->assertStatus(422)
        ->assertJsonStructure([
            'message',
            'errors'
        ]);

        $response = $this->post('/api/auth/login', ['email' => 'wrong@gmail.com', 'password'])
        ->assertStatus(422)
        ->assertJsonStructure([
            'message',
            'errors'
        ]);
    }

    /** @test */
    public function it_should_return_inactive_account_error()
    {
        $response = $this->post('/api/auth/login', ['email' => $this->user->email, 'password' => $this->password])
        ->assertStatus(422)
        ->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'errors'
            ]
        ]);
    }

    /** @test */
    public function it_should_login_successfully()
    {
        $this->user->update(['status' => 'active']);

        $response = $this->post('/api/auth/login', ['email' => $this->user->email, 'password' => $this->password])
        ->assertStatus(200)
        ->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'api_token',
                'user'
            ]
        ]);
    }
}
