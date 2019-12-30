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
}
