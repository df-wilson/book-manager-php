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
}
