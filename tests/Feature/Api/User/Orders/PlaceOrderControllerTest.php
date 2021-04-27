<?php

namespace Tests\Feature\Api\User\Orders;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Str;

class PlaceOrderControllerTest extends TestCase
{
    public $user;
    public $admin;
    public $photographer;
    public $order;

    public function setUp() : void
    {
        parent::setUp();

        User::whereEmail('user@test.com')->delete();
        User::whereEmail('photo@test.com')->delete();
        User::whereEmail('admin@test.com')->delete();

        $this->user = User::create([

            'name' => 'user',
            'email' => 'user@test.com',
            'phone' => '07089898989',
            'password' => bcrypt('password'),
            'type' => 'user',
            'status' => 'active',
            'api_token' => Str::uuid(6)
        ]);

        $this->admin = User::create([

            'name' => 'admin',
            'email' => 'admin@test.com',
            'phone' => '07089898989',
            'password' => bcrypt('password'),
            'type' => 'admin',
            'status' => 'active',
            'api_token' => Str::uuid(6)
        ]);

        $this->photographer = User::create([

            'name' => 'photo',
            'email' => 'photo@test.com',
            'phone' => '07089898989',
            'password' => bcrypt('password'),
            'type' => 'photographer',
            'status' => 'active',
            'api_token' => Str::uuid(6)
        ]);
    }

    /** @test */
    public function it_should_fail_to_place_orders()
    {
        $response = $this->actingAs($this->user, 'api')
        ->post('/api/user/order/create', [])
         ->assertStatus(422)
         ->assertJsonStructure([
             'message',
             'errors'
         ]);

        $response = $this->actingAs($this->user, 'api')
        ->post('/api/user/order/create', ['instructions' => '', 'products' => null])
         ->assertStatus(422)
         ->assertJsonStructure([
             'message',
             'errors'
         ]);
    }

    /** @test */
    public function it_should_create_order_successfully()
    {
        $response = $this->actingAs($this->user, 'api')
        ->post('/api/user/order/create', ['instructions' => '', 'products' => ['Product 1']])
         ->assertStatus(201)
         ->assertJsonStructure([
             'status',
             'message',
             'data'
         ]);
    }

    /** @test */
    public function it_should_assign_order_to_photographer_successfully()
    {
        $this->order = Order::first();
        $this->order->update(['status' => 'pending']);

        $response = $this->actingAs($this->admin, 'api')
        ->put('/api/admin/order/asign-photographer', ['order_id' => $this->order->id, 'photographer_id' => $this->photographer->id])
         ->assertStatus(200)
         ->assertJsonStructure([
             'status',
             'message',
             'data'
         ]);
    }
}
