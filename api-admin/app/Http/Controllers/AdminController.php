<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Book;
use Illuminate\Http\Request;
use Validator;

class AdminController extends Controller
{
    public function __construct(){
        $this->middleware('admin');
    }

    public function addBook(Request $request){
        $checkSession = SessionsController::isSessionActive($request);
        if ($checkSession->getStatusCode() != 200){
            return $checkSession;
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:255',
            'ISBN' => 'required|unique:books|numeric',
            'description' => 'present',
            'publishing_year' => 'required|integer',
            'format' => 'present',
            'pages' => 'required|integer',
            'first_name' => 'required|max:40',
            'last_name' =>  'required|max:40'
        ]);

        if ($validator->fails()){
            $errorMessage = '';
            foreach ($validator->errors()->all() as $error)
                $errorMessage .= $error;
            return response('Invalid params passed. '.$errorMessage, 400);
        }

        $userId = SessionsController::isSessionActive($request)->getContent();
        $user = Admin::find($userId);

        if(!$user->addBook($request)){
            response("Can't add the book", 300);
        }
        return response('The book is added', 200);
    }

    public function deleteBook(){
        $checkSession = SessionsController::isSessionActive(request());
        if ($checkSession->getStatusCode() != 200){
            return $checkSession;
        }

        $bookId = request('book_id');
        $book = Book::find($bookId);

        if (!$book){
            return response('No such a book in collection', 400);
        }

        $book->delete();

        return response('The book is deleted', 200);
    }

    public function updateBookInfo(Request $request){
        $checkSession = SessionsController::isSessionActive($request);
        if ($checkSession->getStatusCode() != 200){
            return $checkSession;
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:255',
            'ISBN' => 'required|unique:books|numeric',
            'description' => 'present',
            'publishing_year' => 'required|integer',
            'format' => 'present',
            'pages' => 'required|integer',
        ]);

        if ($validator->fails()){
            $errorMessage = '';
            foreach ($validator->errors()->all() as $error)
                $errorMessage .= $error;
            return response('Invalid params passed. '.$errorMessage, 400);
        }

        $userId = SessionsController::isSessionActive($request)->getContent();
        $user = Admin::find($userId);

        if(!$user->updateBookInfo()){
            response("Can't update book info", 300);
        }
        return response('The data is updated', 200);
    }
}
