<?php namespace App\Repository;

use DateTime;
use Illuminate\Support\Facades\DB;

class BookRepository
{
    public function getAllForUser(int $userId)
    {
        logger()->debug("Book Repository::getAllForUser - ENTER", ["User Id" => $userId]);

        $books = DB::select("Select * from books WHERE user_id=?", [$userId]);
        return $books;
    }

    public function getByIdForUser(int $id, int $userId)
    {
        logger()->debug("Book Repository::getByIdForUser - ENTER", ["Book Id" => $id, "User Id"=> $userId]);

        $book = DB::select("SELECT * FROM books WHERE id=? AND user_id=?",[$id, $userId]);
        return $book;
    }

    public function store(int $userId, string $title, string $author, int $year, bool $read, int $rating)
    {
        logger()->debug("Book Repository::store - ENTER",
            ["User Id" => $userId, "Title"=> $title, "Author" => $author, "Year" => $year, "Is Read" => $read, "Rating" => $rating]);

        DB::insert("INSERT INTO books (user_id, title, author, year, read, rating, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?)",
            [$userId, $title, $author, $year, $read, $rating, new DateTime(), new DateTime()]);
    }
}

