<?php

namespace App\Http\Controllers;

use App\Book;
use App\User;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    public function __construct(){
        //TODO middleware for auth
    }

    /**
     * Getting User's books list
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function getBookList(Request $request){
        $checkSession = SessionsController::isSessionActive($request);
        if ($checkSession->getStatusCode() != 200){
            return $checkSession;
        }

        $userId = SessionsController::isSessionActive($request)->getContent();

        $bookCount = User::find($userId)->books()->count();

        $offset = $request->has('offset') ? $request->offset : 0;
        $limit = $request->has('limit') ? $request->limit : $bookCount;

        $books = User::find($userId)->books()->skip($offset)->take($limit)->get();
        $header = [ 'Content-Type' => 'application/json; charset=utf-8' ];

        return response()->json($books, 200, $header, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Add a book to User's list
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|string|\Symfony\Component\HttpFoundation\Response
     */
    public function addBook(Request $request){
        $checkSession = SessionsController::isSessionActive($request);
        if ($checkSession->getStatusCode() != 200){
            return $checkSession;
        }

        $userId = SessionsController::isSessionActive($request)->getContent();
        $bookId = $request->input('book_id');

        if (User::find($userId)->books()->find($bookId)){
            return response('You already have this book in collection', 200);
        }

        $book = Book::find($bookId);
        User::find($userId)->books()->attach($book);

        return response('The book is added', 200);
    }

    /**
     * Search for a book
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|string|\Symfony\Component\HttpFoundation\Response
     */
    public function searchBook(Request $request){
        $checkSession = SessionsController::isSessionActive($request);
        if ($checkSession->getStatusCode() != 200){
            return $checkSession;
        }

        $term = $request->has('term') ? $request->term : '';
        $books = Book::where('name', 'like', '%'.$term.'%')->get();

        $header = [ 'Content-Type' => 'application/json; charset=utf-8' ];

        return response()->json($books, 200, $header, JSON_UNESCAPED_UNICODE);
    }
}
