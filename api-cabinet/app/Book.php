<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property string name
 * @property string description
 * @property int publishing_year
 * @property string format
 * @property Author[] authors
 * @property string ISBN
 * @property int pages
 * @property User[] $users
 */
class Book extends Model implements IBook
{
    protected $fillable = [
        'name',
        'ISBN',
        'description',
        'publishing_year',
        'format',
        'pages'
    ];

    public $timestamps = false;

    /**
     * The attributes that should be visible in arrays.
     */
    protected $visible = [
        'id',
        'name',
        'ISBN',
        'description',
        'publishing_year',
        'format',
        'pages'
    ];

    protected $hidden = ['pivot'];


    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        //'format' => 'string',
    ];
    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [
        'author',
    ];

    public function getAuthorAttribute(){
        return $this->emails()
            ->where('user_id', '=', $this->id)
            ->where('is_main', '=', true)
            ->first()
            ->email;
    }

    public function authors(){
        return $this->belongsToMany(Author::class, 'books_authors', 'book_id', 'user_id');
    }

    public function users(){
        return $this->belongsToMany(User::class, 'books_users');
    }

    public function getName(){
        return $this->name;
    }

    public function getISBN(){
        return $this->ISBN;
    }

    public function getAuthors(){
        return $this->authors();
    }

    public function getDescription(){
        return $this->description;
    }

    public function getYearOfPublishing(){
        return $this->publishing_year;
    }

    public function getFormat(){
        return $this->format;
    }

    public function getPagesCount(){
        return $this->pages;
    }

    public function getId(){
        return $this->id;
    }
}
