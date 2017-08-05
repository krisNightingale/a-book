<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property Email[] $emails
 */
abstract class Person extends Model implements IPerson
{
    protected $table = 'users';

    protected $fillable = [
        'first_name',
        'last_name'
    ];

    protected $guarded = ['is_admin'];

    public $timestamps = false;

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'is_admin' => 'boolean',
    ];

    /**
     * The attributes that should be visible in arrays.
     */
    protected $visible = [
        'id',
        'first_name',
        'last_name',
        'email'
    ];

    public function emails(){
        return $this->hasMany(Email::class, 'user_id');
    }

    /**
     * @return bool
     */
    public function hasMain(){
        if (count($this->emails()->where('is_main', '=', 1)->get())){
            return true;
        }
        return false;
    }

    public function getId(){
        return $this->id;
    }

    public function getFirstName(){
        return $this->first_name;
    }

    public function getLastName(){
        return $this->last_name;
    }

    public abstract function getBooks();
}