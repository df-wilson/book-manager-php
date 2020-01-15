<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function testGetAllForUser()
    {
        $this->seed();

        // Test with user not authenticated
        $response = $this->get('/api/v1/books');
        $response->assertStatus(401);

        // Test with user authenticated
        //$user = factory(User::class)->create();
        $user = User::find(1);

        $response = $this->actingAs($user)
            ->get('/api/v1/books');
        
        $response->assertStatus(200);
        $response->assertJsonFragment(["id" => "1",
                                       "user_id" => "1",
                                       "title" => "The Institute",
                                       "author" => "Stephen King",
                                       "year" => "2019",
                                       "read" => "1",
                                       "rating" => "4"]);
        $response->assertJsonFragment([
                                       "id" => "2",
                                       "title" => 'Blue Mars',
                                       "author" => 'Kim Stanley Robinson',
                                       "year" => '1996']);
        $response->assertJsonMissing(["id" => "3",
                                      "user_id" => '2',
                                      "title" => 'Omega',
                                      "author" => 'Jack McDevitt',
                                      "year" => '2002',
                                      "rating" => '5']);
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

    public function testCreate()
    {
        $author = "New author";
        $title = "New title";
        $year = 2019;

        $this->seed();
        
        // Test with user not authenticated
        $response = $this->postJson('/api/v1/books', ['author' => $author, 'title' => $title, 'year' => $year, 'read' => false, 'rating' => 4]);
        $response->assertStatus(401);
        $response->assertJson(["msg" => "Not authorized"]);

        // Test with authenticated user
        $user = User::find(1);
        $response = $this->actingAs($user)->postJson('/api/v1/books', ['author' => $author, 'title' => $title, 'year' => $year, 'read' => false, 'rating' => 4]);
        $response->assertStatus(201);
        $response->assertJson(["msg" => "Book saved", "id" => '4']);

        // Test with authenticated user, but incomplete data.
        $response = $this->actingAs($user)->postJson('/api/v1/books', ['author' => $author, 'title' => $title, 'year' => $year, 'rating' => 4]);
        $response->assertStatus(400);
        $response->assertJson(["msg" => "Invalid data"]);
    }

    public function testUpdate()
    {
        $author = "New author";
        $title = "New title";
        $year = 2020;
        $read = false;
        $rating = 5;

        $this->seed();

        // Test with user not authenticated
        $response = $this->putJson('/api/v1/books/1', ['author' => $author, 'title' => $title, 'year' => $year, 'read' => $read, 'rating' => 4]);
        $response->assertStatus(401);
        $response->assertJson(["msg" => "Not authorized"]);

        // Test with authenticated user
        $user = User::find(1);
        $response = $this->actingAs($user)->putJson('/api/v1/books/1', ['author' => $author, 'title' => $title, 'year' => $year, 'read' => $read, 'rating' => $rating]);
        $response->assertStatus(200);
        $response->assertJson(["msg" => "Book updated", "id" => '1']);

        $response = $this->actingAs($user)->get('/api/v1/books/1');
        $response->assertStatus(200);
        $response->assertJson([
            'id' => '1',
            'user_id' => '1',
            'author'  => $author,
            'title'   => $title,
            'year'    => $year,
            'read'    => $read,
            'rating'  => $rating
        ]);

        // Test with authenticated user, but incomplete data.
        $response = $this->actingAs($user)->putJson('/api/v1/books/1', ['author' => $author, 'title' => $title, 'year' => $year, 'rating' => 4]);
        $response->assertStatus(400);
        $response->assertJson(["msg" => "Invalid data"]);
    }

    public function testDelete()
    {
        $this->seed();

        // Test with user not authenticated
        $response = $this->delete('/api/v1/books/1');
        $response->assertStatus(401);
        $response->assertJson(["msg" => "Not authorized"]);

        // Test with authenticated user
        $user = User::find(1);
        $response = $this->actingAs($user)->delete('/api/v1/books/1');
        $response->assertStatus(200);
        $response->assertJson(["msg" => "Book deleted"]);

        // Test with user authenticated, but is not owner of book.
        $user = User::find(1);
        $response = $this->actingAs($user)->delete('/api/v1/books/3');
        $response->assertStatus(404);
        $response->assertJson(["msg" => "Book not found for user"]);
    }
}
