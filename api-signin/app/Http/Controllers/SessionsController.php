<?php

namespace App\Http\Controllers;

use App\Email;
use App\Models\UserSession;

class SessionsController extends Controller
{
    public function create(){
        return view('sessions.create');
    }

    /**
     * Checking session
     * @return bool | string
     */
    public static function checkSession(){

        $userSession = resolve('UserSession');

        if (!$userSession){
            return response('Authorization Required', 401);
        }
        if (!$userSession->check()){
            return response('Access Forbidden', 403);
        }

        //TODO prolong if expired

        return response($userSession->getUserId(), 200);
    }
    /**
     * Expiring a user's session, a.k.a. logout
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy(){

        $userSession = resolve('UserSession');

        if (!$userSession){
            return response('Authorization Required', 401);
        }

        if (!$userSession->expire()){
            return response('Access Forbidden', 403);
        }

        return response('Session were expired', 200);
    }

    /**
     * Prolong a user's session
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function prolong(){

        $userSession = resolve('UserSession');

        if (!$userSession){
            return response('Authorization Required', 401);
        }

        if ($userSession->prolong()){
            response('Session prolonged', 200);
        }

        return response('Access Forbidden', 403);
    }


    /**
     * Authorize a user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function signIn(){
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

        $userSession = resolve('UserSession');
        if ($userSession){
            return response('You are already authorized', 302)
                ->header('X-CSRF-TOKEN', $userSession->getToken());
        }
        $userSession = new UserSession();
        $userSession->create($userID);

        return response('Authorization OK', 200)
            ->header('X-CSRF-TOKEN', $userSession->getToken());
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
