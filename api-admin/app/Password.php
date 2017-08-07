<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property Email $email
 */
class Password extends Model
{
    public $incrementing = false;
    public $timestamps = false;
    public $fillable = ['password', 'email_id'];

    public function email(){
        return $this->belongsTo(Email::class);
    }

    public function user(){
        return $this->email->user;
    }
}
