<?php

namespace App\Services;

use App\Mail\Welcome;
use Illuminate\Support\Facades\Mail;

class MailService
{
    public function sendRegistrationEmail($user){
        Mail::to($user)->send(new Welcome($user));
        return true;
    }
}