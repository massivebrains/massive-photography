<?php

namespace Tests\Feature\Api\User\Orders;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Str;

class OrdersControllerTest extends TestCase
{
    public $user;

    public function setUp() : void
    {
        parent::setUp();

        User::whereEmail('user@test.com')->delete();

        $this->user = User::create([

            'name' => 'user',
            'email' => 'user@test.com',
            'phone' => '07089898989',
            'password' => bcrypt('password'),
            'type' => 'user',
            'status' => 'active',
            'api_token' => Str::uuid(6)
        ]);
    }

    /** @test */
    public function it_should_return_orders()
    {
        $response = $this->actingAs($this->user, 'api')
        ->get('/api/user/orders')
         ->assertStatus(200)
         ->assertJsonStructure([
             'status',
             'message',
             'data'
         ]);
    }
}
