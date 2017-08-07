<?php

namespace App;

use Illuminate\Http\Request;

/**
 * @property array $emails
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property boolean $is_admin
 * @property string $email
 * @property string $password
 */
class User extends Person implements IUser
{
    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [
        'email',
        'main'
    ];

    public function getEmailAttribute(){
        return $this->emails()
            ->where('user_id', '=', $this->id)
            ->where('is_main', '=', true)
            ->first()
            ->email;
    }

    public function getPasswordAttribute(){
        return $this->password()
            ->first()
            ->password;
    }

    public function password(){
        return $this->hasManyThrough(Password::class, Email::class);
    }

    public function books(){
        return $this->belongsToMany(Book::class, 'books_users');
    }

    /**
     * @return Email
     */
    public function main(){
        return  $this->emails()
            ->where('is_main', '=', 1)
            ->get()->first();
    }

    public function addMainEmail($email, $password){
        $email = $this->emails()->create(compact('email'));
        $email -> createPassword($password);
    }

    public function addEmail($email){
        $this->emails()->create([
            'email' => $email,
            'user_id' => $this->id,
            'is_main' => 0
        ]);
    }

    /**
     * @return Book[]
     */
    public function getBooks(){
        return $this->books()->get();
    }

    public function getEmail(){
        return $this->main();
    }
}
