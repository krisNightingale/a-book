<?php

namespace App\Http\Controllers;

use App\Email;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Session\Session;

class SessionsController extends Controller
{
    //TODO Guest/Auth MIDDLEWARE
    public function __construct(){
        //$this->middleware('guest', ['except' => 'destroy']);
    }

    public function create(){
        return view('sessions.create');
    }

    /**
     * Checking session
     * @param Request $request
     * @return bool | string
     */
    public static function checkSession(Request $request){
        //A session key retrieved when authorized
        $token = $request->header('X-CSRF-TOKEN');
        $user = Cache::get($token);

        if (!$token){
            return response('Authorization Required', 401);
        }
        if (!$user){
            return response('Access Forbidden', 403);
        }
        return response($user, 200);
    }
    /**
     * Expiring a user's session, a.k.a. logout
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy(Request $request){

        //A session key retrieved when authorized
        $token = $request->header('X-CSRF-TOKEN');

        if (!$token){
            return response('Authorization Required', 401);
        }

        if (!Cache::pull($token)){
            return response('Access Forbidden', 403);
        }

        return response('Session were expired', 200);
    }

    /**
     * Prolong a user's session
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function prolong(Request $request){
        //A session key retrieved when authorized
        $token = $request->header('X-CSRF-TOKEN');

        if (!$token){
            return response('Authorization Required', 401);
        }

        $cacheInstance = Cache::get($token);
        $expiresAt = Carbon::now()->addMinutes(60);

        if ($cacheInstance){
            Cache::put($token, $cacheInstance, $expiresAt);
            return response('Session prolonged', 200);
        }

        return response('Access Forbidden', 403);
    }


    /**
     * Authorize a user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(){
        $this->validate(request(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $email = request('email');
        $password= request('password');

        $userID = self::getUserId($email, $password);

        if (!$userID){
            return response('Access forbidden', 403);
        }

        $expiresAt = Carbon::now()->addMinutes(60);
        $token = self::generateToken($userID.$email);

        Cache::add($token, $userID, $expiresAt);

        return response('Authorization OK', 200)
            ->header('X-CSRF-TOKEN', $token);
    }

    /**
     * @param string $string
     * @return string
     */
    public static function generateToken($string){
        return md5($string);
    }

    /**
     * @param string $email
     * @param string $password
     * @return bool
     */
    public static function checkCredentials($email, $password){
        if (self::getUserId($email, $password)){
            return true;
        }
        return false;
    }

    /**
     * @param string $email
     * @param string $password
     * @return mixed
     */
    public static function getUserId($email, $password){

        //User::join('emails', 'users.id', '=', 'emails.user_id')
        $credentials = Email::where('emails.is_main', '=', '1')
            ->join('passwords', 'emails.id', '=', 'passwords.email_id')
            ->where('email', '=', $email)
            ->where('password', '=', $password)
            ->get()
            ->first();

        if (count($credentials)){
            return $credentials->user_id;
        }

        return false;
    }
}
