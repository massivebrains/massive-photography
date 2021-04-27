<?php

namespace Tests\Feature\Api\Admin\Accounts;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Str;

class AccountsControllerTest extends TestCase
{
    public $admin;

    public function setUp() : void
    {
        parent::setUp();

        User::whereEmail('admin@test.com')->delete();

        $this->admin = User::create([

            'name' => 'Admin',
            'email' => 'admin@test.com',
            'phone' => '07089898989',
            'password' => bcrypt('password'),
            'type' => 'admin',
            'status' => 'active',
            'api_token' => Str::uuid(6)
        ]);
    }

    /** @test */
    public function it_should_return_accounts()
    {
        $response = $this->actingAs($this->admin, 'api')
        ->get('/api/admin/accounts')
         ->assertStatus(200)
         ->assertJsonStructure([
             'status',
             'message',
             'data'
         ]);
    }
}
