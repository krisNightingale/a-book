<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * @property int $id
 * @property boolean $is_main
 * @property string $email
 * @property int $user_id
 * @property Password $password
 * @property User $user
 */
class Email extends Model
{
    public $fillable = ['email', 'user_id', 'is_main'];
    public $timestamps = false;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function password(){
        return $this->hasOne(Password::class);
    }

    public function createPassword($password){
        $this->password()->create(compact('password'));
    }

    /**
     * @return bool
     */
    public function isMain(){
        return $this->is_main ? true : false;
    }
}
