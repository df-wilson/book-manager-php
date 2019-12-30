<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    
    public function testGetAllForUser()
    {
        $this->seed();

        // Test with user not authenticated
        $response = $this->get('/api/v1/books');
        $response->assertStatus(401);

        // Test with user authenticated
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
            ->withSession(['foo' => 'bar'])
            ->get('/api/v1/books');
        $response->assertStatus(200);



    }
}
