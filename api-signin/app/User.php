<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

/**
 * @property array $emails
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property boolean $is_admin
 * @property string $email
 * @property string $password
 */
class User extends Model
{
    use Notifiable;

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


    public function emails(){
        return $this->hasMany(Email::class);
    }

    public function password(){
        return $this->hasManyThrough(Password::class, Email::class);
    }

    /**
     * @return Email
     */
    public function main(){
        return  $this->emails()
            ->where('is_main', '=', 1)
            ->get()->first();
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

    /**
     * @return bool
     */
    public function isMember(){
        return ($this->hasMain()) ? true : false;
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

    //TODO: password mutator

}
