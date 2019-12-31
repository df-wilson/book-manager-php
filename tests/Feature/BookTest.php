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

    public function testGetByIdForUser()
    {
        $this->seed();

        // Test with user not authenticated
        $response = $this->get('/api/v1/books/1');
        $response->assertStatus(401);
        $response->assertJson([]);

        // Test with user authenticated
        $user = User::find(1);

        $response = $this->actingAs($user)->get('/api/v1/books/1');
        $response->assertStatus(200);
        $response->assertJson([
            'id' => '1',
            'user_id' => '1',
            'author'  => 'Stephen King',
            'title'   => 'The Institute',
            'year'    => '2019',
            'read'    => '1',
            'rating'  => '4'
        ]);

        // Test with book that does not belong to user
        $response = $this->actingAs($user)->get('/api/v1/books/3');
        $response->assertStatus(403);
        $response->assertJson([]);

    }
}
