<?php namespace App\Repository;

use DateTime;
use Illuminate\Support\Facades\DB;

class BookRepository
{
    const SEARCH_TYPE_AUTHOR = 0;
    const SEARCH_TYPE_TITLE  = 1;
    const SEARCH_TYPE_BOTH   = 2;

    public function deleteForUser(int $id, int $userId) : int
    {
        logger()->debug("Book Repository::delete - ENTER",
            ["Book Id" => $id, "User Id" => $userId]);

        $numBooksDeleted = DB::delete("DELETE FROM books WHERE id=? AND user_id=?",
            [$id, $userId]);

        logger()->debug("Book Repository::delete - LEAVE",
            ["Number of books deleted" => $numBooksDeleted]);

        return $numBooksDeleted;
    }

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

    public function search(int $userId, int $searchType, string $searchTerm)
    {
        logger()->debug("Book Repository::search - ENTER",
            ["User Id" => $userId, "SearchType" => $searchType, "Search Term" => $searchTerm]);

         $books = [];

        $searchQuery = "SELECT id, user_id, title, author, year, read, rating FROM books WHERE user_id = :user_id AND ";
        switch($searchType)
        {
        case self::SEARCH_TYPE_AUTHOR:
           $searchQuery .= "author LIKE :search_term";
           break;

        case self::SEARCH_TYPE_TITLE:
           $searchQuery .= "title LIKE :search_term";
           break;

        case self::SEARCH_TYPE_BOTH:
        default:
            $searchQuery .= "(title LIKE :search_term OR author LIKE :search_term)";
        }

        $searchString = "%$searchTerm%";

        $books = DB::select($searchQuery, ['user_id' => $userId, 'search_term' => $searchString]);

       return $books;
    }

    public function store(int $userId, string $title, string $author, int $year, bool $read, int $rating)
    {
        logger()->debug("Book Repository::store - ENTER",
            ["User Id" => $userId, "Title" => $title, "Author" => $author, "Year" => $year, "Is Read" => $read, "Rating" => $rating]);

        DB::insert("INSERT INTO books (user_id, title, author, year, read, rating, created_at, updated_at) VALUES (?,?,?,?,?,?,?,?)",
            [$userId, $title, $author, $year, $read, $rating, new DateTime(), new DateTime()]);

        $id = DB::getPdo()->lastInsertId();

        logger()->debug("BookRepository::store - LEAVE", ["Book Id" => $id]);

        return $id;
    }

    public function update(int $userId, int $id, string $title, string $author, int $year, bool $read, int $rating)
    {
        logger()->debug("Book Repository::update - ENTER",
            ["Id" => $id, "User Id" => $userId, "Title" => $title, "Author" => $author, "Year" => $year, "Is Read" => $read, "Rating" => $rating]);

        $affected = DB::update("UPDATE books set title=?, author=?, year=?, read=?, rating=?, updated_at=? WHERE id=? AND user_id=?",
            [$title, $author, $year, $read, $rating, new DateTime(), $id, $userId]);

        if($affected > 1) {
            logger()->error("BookRepository::update - ERROR: More than 1 row was updated",
                ["Rows affected" => $affected]);
        }

        logger()->debug("BookRepository::update - LEAVE",
            ["Rows affected" => $affected]);

        return $affected;
    }
}

