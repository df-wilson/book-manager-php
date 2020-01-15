<?php

namespace App\Http\Controllers;

use App\Repository\BookRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function index()
    {
        $id = Auth::id();

        if($id) {
            $repository = new BookRepository();
            $books = $repository->getAllForUser($id);
            $statusCode = 200;
        } else {
            $books = [];
            $statusCode = 401;
        }
        $response = ["books" => $books];
        
        return response()->json($response, $statusCode);
    }

    public function getById(int $id)
    {
        logger()->info("BookController::getById - ENTER", ["Id" => $id]);

        $userId = Auth::id();

        if($userId)
        {
            $repository = new BookRepository();
            $result = $repository->getByIdForUser($id, $userId);
            if(count($result))
            {
                $statusCode = 200;
                $book = $result[0];
            }
            else
            {
                $statusCode = 403;
                $book = null;
            }
        }
        else
        {
            $book = null;
            $statusCode = 401;
        }

        logger()->debug("BookController::getById - LEAVE", ["Code" => $statusCode, "Book" => $book]);

        return response()->json($book, $statusCode);
    }

    public function create(Request $request)
    {
        logger()->info("BookController::create - ENTER", ["Request" => $request->all()]);

        $statusCode = 500;
        $message = "Server error";

        $userId = Auth::id();
        logger()->debug("BookController::create", ["User Id" => $userId]);

        if($userId)
        {
            if ($request->has(['author', 'title', 'year', 'read', 'rating']))
            {
                $repository = new BookRepository();
                $bookId =$repository->store($userId,
                                            $request->input('author'),
                                            $request->input('title'),
                                            $request->input('year'),
                                            $request->input('read'),
                                            $request->input('rating'));

                $message = ["msg" => "Book saved", "id" => $bookId];
                $statusCode = 201;
            }
            else
            {
                $message = ["msg" => "Invalid data"];
                $statusCode = 400;
            }
        }
        else
        {
            $message = ["msg" => "Not authorized"];
            $statusCode = 401;
        }

        logger()->debug("BookController::create - LEAVE", ["Code" => $statusCode, "Message" => $message]);

        return response()->json($message, $statusCode);
    }

    public function delete(int $id)
    {
        logger()->info("BookController::delete - ENTER", ["Book Id" => $id]);

        $statusCode = 500;
        $message = "Server error";

        $userId = Auth::id();
        logger()->debug("BookController::delete", ["User Id" => $userId]);

        if ($userId)
        {
            $repository = new BookRepository();
            $numBooksDeleted = $repository->deleteForUser($id, $userId);

            if($numBooksDeleted == 1)
            {
                $message = ["msg" => "Book deleted", "book id" => $id];
                $statusCode = 200;
            }
            else if($numBooksDeleted == 0)
            {
                $message = ["msg" => "Book not found for user", "book id" => $id];
                $statusCode = 404;
            }
            else
            {
                $message = ["msg" => "server error", "book id" => $id];
                $statusCode = 500;
            }
        }
        else
        {
            $message = ["msg" => "Not authorized"];
            $statusCode = 401;
        }

        logger()->debug("BookController::delete - LEAVE", ["Code" => $statusCode, "Message" => $message]);

        return response()->json($message, $statusCode);
    }

    public function search(Request $request, string $search)
    {
        logger()->info("BookController::search - ENTER", ["Query Type" => $request->query(), "Search Term" => $search]);

        $statusCode = 500;
        $message = ["msg" => "Server error"];
        $books = ['books' => []];

        $userId = Auth::id();
        logger()->debug("BookController::search", ["User Id" => $userId]);

        if($userId)
        {
            $searchType = strtolower($request->query('searchType'));
            $repository = new BookRepository();
            $results = $repository->search($userId, $searchType, $search);

            logger()->debug("BookController::search", ["Results" => $results]);
            $message = ["msg" => "ok", "books" => $results];
            $statusCode = 200;
        }
        else
        {
            $message = ["msg" => "Not authorized", "books" => []];
            $statusCode = 401;
        }

        logger()->debug("BookController::search - LEAVE", ["Code" => $statusCode, "Message" => $message]);

        return response()->json($message, $statusCode);
    }

    public function update(int $id, Request $request)
    {
        logger()->info("BookController::update - ENTER", ["Request" => $request->all()]);

        $statusCode = 500;
        $message = "Server error";

        $userId = Auth::id();
        logger()->debug("BookController::update", ["User Id" => $userId]);

        if($userId)
        {
            if ($request->has(['author', 'title', 'year', 'read', 'rating']))
            {
                $repository = new BookRepository();
                $rowsUpdated = $repository->update($userId,
                               $id,
                               $request->input('title'),
                               $request->input('author'),
                               $request->input('year'),
                               $request->input('read'),
                               $request->input('rating'));

                if($rowsUpdated > 1) {
                    logger()->error("BookRepository::update - ERROR: More than 1 row was updated",
                        ["Rows updated" => $rowsUpdated]);
                }

                $message = ["msg" => "Book updated", "id" => $request->input('id')];
                $statusCode = 200;
            }
            else
            {
                $message = ["msg" => "Invalid data"];
                $statusCode = 400;
            }
        }
        else
        {
            $message = ["msg" => "Not authorized"];
            $statusCode = 401;
        }

        logger()->debug("BookController::update - LEAVE", ["Code" => $statusCode, "Message" => $message]);

        return response()->json($message, $statusCode);
    }
}
