<?php

namespace App\Http\Controllers;

use App\Email;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Validator;

class PasswordsController extends Controller
{
    /**
     * Set a user's password
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function setPassword(Request $request){

        $this->validate($request, [
            'password' => 'required'
        ]);

        //A session key retrieved when authorized
        $token = $request->header('X-CSRF-TOKEN');
        $password = $request->input('password');

        if (!$token){
            return response('Authorization Required', 401);
        }

        $userId = Cache::get($token);

        if (!$userId){
            return response('Access Forbidden', 403);
        }

        $user = User::find($userId);

        $user->password()->update(compact('password'));

        return response('Session were expired', 200);
    }

    /**
     * Set a user's password by token
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function setPasswordByToken(Request $request){

        $this->validate($request, [
            'mailToken' => 'required',
            'password' => 'required'
        ]);

        $mailToken = $request->input('mailToken');
        $password = $request->input('password');

        if (!$mailToken){
            return response('Token Required', 401);
        }

        $userId = Cache::pull($mailToken);

        if (!$userId){
            return response('Invalid Token were passed', 403);
        }

        $user = User::find($userId);
        $email = $user->email;

        $token = SessionsController::generateToken($userId.$email);

        Cache::pull($token);

        $user->password()->update(compact('password'));

        return response('New password was set', 200);
    }

    /**
     * Request a password reset with a mail
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function requestPasswordReset(Request $request){
        $email = $request->input('email');

        $validator = Validator::make($email, [
            'email' => 'required|email|unique:emails'
        ]);

        if ($validator->fails()){
            return response('Access Forbidden', 403);
        }

        $mailToken = str_random(64);

        $user = Email::where('email', '=', $email)
            ->first()
            ->user;

        //During this time the reference into the mail will be active.
        $expiresAt = Carbon::now()->addMinutes(360);

        Cache::add($mailToken, $user->id, $expiresAt);

        app('MailService')->sendPasswordResetEmail($user, $mailToken, $expiresAt);

        return response('Reset message was requested', 200);
    }
}
