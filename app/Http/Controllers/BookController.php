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

        return response()->json($books, $statusCode);
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
}
