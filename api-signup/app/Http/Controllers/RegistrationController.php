<?php

namespace App\Http\Controllers;

use App\User;
use Validator;


class RegistrationController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function create(){
        return view('registration.create');
    }

    public function register(){

        $checkSession = SessionsController::isSessionActive(request());
        if ($checkSession->getStatusCode() == 200){
            return response('You are already registered', 302);
        }

        $validator = Validator::make(request()->all(), [
            'first_name' => 'required|min:2|max:40',
            'last_name' => 'required|min:2|max:40',
            'email' => 'required|email|unique:emails',
            'password' => 'required|min:4|max:40|confirmed'
        ]);

        if ($validator->fails()){
            $errorMessage = '';
            foreach ($validator->errors()->all() as $error)
                $errorMessage .= $error;
            return response('Invalid params passed. '.$errorMessage, 400);
        }

       $user = User::create([
            'first_name' => request('first_name'),
            'last_name' => request('last_name'),
        ]);

        $user->addMainEmail(request('email'), request('password'));

        app('MailService')->sendRegistrationEmail($user);

        return response('Successful registration', 200);
    }
}
