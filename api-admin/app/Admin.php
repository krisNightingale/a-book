<?php

namespace App;

use Illuminate\Http\Request;

class Admin extends User
{
    public function addBook(Request $request){
        $author = Author::where('first_name', 'like', $request['first_name'])
            ->where('last_name', 'like', $request['last_name'])
            ->get()
            ->first();
        if (!$author){
            $author = Author::create($request->only('first_name', 'last_name'));
        }
        $author->books()->create($request->all());
        return true;
    }

    public function updateBookInfo(){
        $book = Book::find(request('book_id'));
        $book->update(request()->all());
    }
}