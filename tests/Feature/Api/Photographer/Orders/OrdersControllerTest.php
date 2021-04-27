<?php

namespace Tests\Feature\Api\Photographer\Orders;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Str;

class OrdersControllerTest extends TestCase
{
    public $photographer;

    public function setUp() : void
    {
        parent::setUp();

        User::whereEmail('photo@test.com')->delete();

        $this->photographer = User::create([

            'name' => 'photographer',
            'email' => 'photo@test.com',
            'phone' => '07089898989',
            'password' => bcrypt('password'),
            'type' => 'photographer',
            'status' => 'active',
            'api_token' => Str::uuid(6)
        ]);
    }

    /** @test */
    public function it_should_return_orders()
    {
        $response = $this->actingAs($this->photographer, 'api')
        ->get('/api/photographer/orders')
         ->assertStatus(200)
         ->assertJsonStructure([
             'status',
             'message',
             'data'
         ]);
    }
}
