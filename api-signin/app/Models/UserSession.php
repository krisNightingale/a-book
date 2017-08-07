<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class UserSession
{
    protected $userId;
    protected $expiresAt;
    protected $token;

    public function __construct($token = null)
    {
        $this->token = $token;
    }

    public function create($userId){
        $this->userId = $userId;
        $expiresAt = Carbon::now()->addMinutes(60);
        $this->expiresAt = $expiresAt;

        $email = $this->getUser($userId)->email;
        $this->token = self::generateToken($userId.$email.config('app.cache_key'));

        return $this->save();
    }

    /**
     * @param string $string
     * @return string
     */
    public static function generateToken($string){
        return md5($string);
    }

    public function getUser($userId){
        return User::find($userId);
    }

    public function save(){
        return Cache::add($this->token, [
            'userId' => $this->userId,
            'expiresAt' => $this->expiresAt
        ], $this->expiresAt);
    }

    public function getExpiresAt(){
        $expiresAt = Cache::get($this->token);
        return $expiresAt['expiresAt'];
    }

    public function getUserId(){
        $userId = Cache::get($this->token);
        return $userId['userId'];
    }

    public function getToken(){
        return $this->token;
    }

    public function prolong(){
        $cacheInstance = Cache::get($this->token);
        $expiresAt = Carbon::now()->addMinutes(60);

        if ($cacheInstance){
            Cache::put($this->token, $cacheInstance, $expiresAt);
            return true;
        }

        return false;
    }

    public function expire(){
        if (!Cache::pull($this->token)){
            return false;
        }
        return true;
    }

    public function check(){
        if (!Cache::get($this->token)){
            return false;
        }
        return true;
    }
}