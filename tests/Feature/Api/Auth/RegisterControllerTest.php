<?php

namespace Tests\Feature\Api\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Str;

class RegisterControllerTest extends TestCase
{
    public $payload;

    public function setUp() : void
    {
        parent::setUp();

        User::whereEmail('random-user@gmail.com')->delete();

        $this->payload = [
            'name' => 'User Name',
            'email' => 'random-user@gmail.com',
            'phone' => '09060051239',
            'address' => 'Fake Address',
            'password' => 'password',
            'password_confirmation' => 'password',
            'type' => 'user'
        ];
    }

    /** @test */
    public function it_should_ensure_registration_fails_with_invalid_credentials()
    {
        $response = $this->post('/api/auth/register', [])
        ->assertStatus(422)
        ->assertJsonStructure([
            'message',
            'errors'
        ]);

        $invalid_payload = $this->payload;
        unset($invalid_payload['email']);

        $response = $this->post('/api/auth/login', $invalid_payload)
        ->assertStatus(422)
        ->assertJsonStructure([
            'message',
            'errors'
        ]);
    }

    /** @test */
    public function it_should_register_successfully()
    {
        $response = $this->post('/api/auth/register', $this->payload)
         ->assertStatus(201)
         ->assertJsonStructure([
             'status',
             'message',
             'data'
         ]);
    }
}
