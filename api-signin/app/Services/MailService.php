<?php

namespace App\Services;

use App\Mail\PasswordReset;
use Illuminate\Support\Facades\Mail;

class MailService
{
    public function sendPasswordResetEmail($user, $mailToken, $expiresAt){
        Mail::to($user)->send(new PasswordReset($user, $mailToken, $expiresAt));
        return true;
    }
}