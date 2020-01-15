<?php

namespace Tests\Unit;

use App\Repository\BookRepository;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();
        factory(User::class, 3)->create();
    }

    public function testGetAllForUser()
    {
        $this->seed();

        $repository = new BookRepository();
        $books = $repository->getAllForUser($this->user1Id);
        $numBooks = count($books);

        $this->assertEquals($numBooks, 2);

        $this->assertEquals($books[0]->id, 1);
        $this->assertEquals($books[0]->user_id, $this->user1Id);
        $this->assertEquals($books[0]->title, "The Institute");
        $this->assertEquals($books[0]->author, "Stephen King");
        $this->assertEquals($books[0]->year, 2019);
        $this->assertEquals($books[0]->read, true);
        $this->assertEquals($books[0]->rating, 4);
        $this->assertNotNull($books[0]->created_at);
        $this->assertNotNull($books[0]->updated_at);

        $this->assertEquals($books[1]->id, 2);
        $this->assertEquals($books[1]->user_id, $this->user1Id);
        $this->assertEquals($books[1]->title, "Blue Mars");
        $this->assertEquals($books[1]->author, "Kim Stanley Robinson");
        $this->assertEquals($books[1]->year, 1996);
        $this->assertEquals($books[1]->read, true);
        $this->assertEquals($books[1]->rating, 4);
        $this->assertNotNull($books[1]->created_at);
        $this->assertNotNull($books[1]->updated_at);

        $repository = new BookRepository();
        $books = $repository->getAllForUser($this->user2Id);
        $numBooks = count($books);

        $this->assertEquals($numBooks, 1);
        $this->assertEquals($books[0]->id, 3);
        $this->assertEquals($books[0]->user_id, $this->user2Id);
        $this->assertEquals($books[0]->title, "Omega");
        $this->assertEquals($books[0]->author, "Jack McDevitt");
        $this->assertEquals($books[0]->year, 2002);
        $this->assertEquals($books[0]->read, true);
        $this->assertEquals($books[0]->rating, 5);
        $this->assertNotNull($books[0]->created_at);
        $this->assertNotNull($books[0]->updated_at);
    }

    public function testGetByIdForUser()
    {
        $this->seed();
        
        $repository = new BookRepository();
        $books = $repository->getByIdForUser(2, $this->user1Id);
        $numBooks = count($books);
        $this->assertEquals($numBooks, 1);
    }

    public function testStore()
    {
        $repository = new BookRepository();
        $repository->store($this->user2Id, $this->title1, $this->author1, $this->year1, $this->isRead1, $this->rating1);
        
        $book = $repository->getByIdForUser(1,2);

        $this->assertEquals(count($book), 1);
        $this->assertEquals($book[0]->id, 1);
        $this->assertEquals($book[0]->user_id, $this->user2Id);
        $this->assertEquals($book[0]->title, "The Expanse");
        $this->assertEquals($book[0]->author, "James S.A. Corey");
        $this->assertEquals($book[0]->year, 2014);
        $this->assertEquals($book[0]->read, true);
        $this->assertEquals($book[0]->rating, 5);
        $this->assertNotNull($book[0]->created_at);
        $this->assertNotNull($book[0]->updated_at);
    }

    public function testUpdate()
    {
        $this->seed();

        $repository = new BookRepository();

        // Test trying to update book that does not belong to user.
        $repository->update(1, $this->user2Id, $this->updatedTitle,$this->updatedAuthor, $this->updatedYear, $this->updatedIsRead, $this->updatedRating);

        $book = $repository->getByIdForUser(1, $this->user1Id);
        $numBooks = count($book);

        $this->assertEquals($numBooks, 1);
        $this->assertEquals($book[0]->id, 1);
        $this->assertEquals($book[0]->user_id, $this->user1Id);
        $this->assertEquals($book[0]->title, "The Institute");
        $this->assertEquals($book[0]->author, "Stephen King");
        $this->assertEquals($book[0]->year, 2019);
        $this->assertEquals($book[0]->read, true);
        $this->assertEquals($book[0]->rating, 4);
        $this->assertNotNull($book[0]->created_at);
        $this->assertNotNull($book[0]->updated_at);

        $origCreated = $book[0]->created_at;
        $origUpdated = $book[0]->updated_at;

        // Test valid update
        $numRowsUpdated = $repository->update($this->user1Id, 1, $this->updatedTitle,$this->updatedAuthor, $this->updatedYear, $this->updatedIsRead, $this->updatedRating);
        $this->assertEquals(1, $numRowsUpdated);
        
        $book = $repository->getByIdForUser(1, $this->user1Id);
        $numBooks = count($book);

        $this->assertEquals($numBooks, 1);
        $this->assertEquals($book[0]->id, 1);
        $this->assertEquals($book[0]->user_id, $this->user1Id);
        $this->assertEquals($book[0]->title, $this->updatedTitle);
        $this->assertEquals($book[0]->author, $this->updatedAuthor);
        $this->assertEquals($book[0]->year, $this->updatedYear);
        $this->assertTrue($book[0]->read == $this->updatedIsRead);
        $this->assertEquals($book[0]->rating, $this->updatedRating);
        $this->assertEquals($book[0]->created_at, $origCreated);
        $this->assertNotEquals($book[0]->updated_at, $origUpdated);
    }

    public function testDeleteBook()
    {
        $this->seed();

        $repository = new BookRepository();

        // Test trying to delete a book that does not belong to user
        $numBooksDeleted = $repository->deleteForUser(2, $this->user2Id);
        $this->assertEquals($numBooksDeleted, 0);

        $book = $repository->getByIdForUser(2, $this->user1Id);
        $this->assertEquals(count($book), 1);
        $this->assertEquals($book[0]->id, 2);
        $this->assertEquals($book[0]->user_id, $this->user1Id);
        $this->assertEquals($book[0]->title, "Blue Mars");
        $this->assertEquals($book[0]->author, "Kim Stanley Robinson");
        $this->assertEquals($book[0]->year, 1996);
        $this->assertEquals($book[0]->read, true);
        $this->assertEquals($book[0]->rating, 4);
        $this->assertNotNull($book[0]->created_at);
        $this->assertNotNull($book[0]->updated_at);

        // Test deleting a book that belongs to user
        $numBooksDeleted = $repository->deleteForUser(2, $this->user1Id);
        $this->assertEquals($numBooksDeleted, 1);

        $book = $repository->getByIdForUser(2, $this->user1Id);
        $this->assertEquals(count($book), 0);
    }

    public function testSearch()
    {
        $this->seed();

        $repository = new BookRepository();


        $books = $repository->search(1,"author", "King");

        $this->assertEquals(count($books), 1);
        $this->assertEquals($books[0]->id, 1);
        $this->assertEquals($books[0]->user_id, $this->user1Id);
        $this->assertEquals($books[0]->title, "The Institute");
        $this->assertEquals($books[0]->author, "Stephen King");
        $this->assertEquals($books[0]->year, 2019);
        $this->assertEquals($books[0]->read, true);
        $this->assertEquals($books[0]->rating, 4);

        // Test search is not case sensitive
        $books = $repository->search(1,"author", "king");

        $this->assertEquals(count($books), 1);
        $this->assertEquals($books[0]->id, 1);
        $this->assertEquals($books[0]->user_id, $this->user1Id);
        $this->assertEquals($books[0]->title, "The Institute");
        $this->assertEquals($books[0]->author, "Stephen King");
        $this->assertEquals($books[0]->year, 2019);
        $this->assertEquals($books[0]->read, true);
        $this->assertEquals($books[0]->rating, 4);

        // Test search for user 2 who does not have any books with author 'King'
        $books = $repository->search(2,"author", "King");
        $this->assertEquals(0, count($books));

        $books = $repository->search(1,"title", "King");
        $this->assertEquals(0, count($books));

        $books = $repository->search(1,"title", "Mars");
        $this->assertEquals(1, count($books));

        $books = $repository->search(1,"both", "King");
        $this->assertEquals(count($books), 1);

        $books = $repository->search(1,"both", "Mars");
        $this->assertEquals(count($books), 1);

    }

    private $user1Id = 1;
    private $user2Id = 2;
    private $invalidUserId = 1000;
    private $title1 = "The Expanse";
    private $author1 = "James S.A. Corey";
    private $year1 = 2014;
    private $isRead1 = true;
    private $rating1 = 5;
    private $updatedTitle = "1984";
    private $updatedAuthor = "George Orwell";
    private $updatedYear = 1949;
    private $updatedIsRead = false;
    private $updatedRating = 5;
}
