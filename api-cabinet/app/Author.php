<?php

namespace App;

class Author extends Person implements IAuthor
{

    public function books(){
        return $this->belongsToMany(Book::class, 'books_authors', 'user_id','book_id');
    }

    /**
     * @return Book[]
     */
    public function getBooks(){
        return $this->books();
    }

    /**
     * @return bool
     */
    public function isMember(){
        return ($this->hasMain()) ? true : false;
    }

    /**
     * @return IUser | null
     */
    public function getUser(){
        if ($this->isMember()){
            return User::find($this->id);
        }
        return null;
    }
}