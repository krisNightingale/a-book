<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PasswordReset extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $mailToken;
    public $expiresAt;

    /**
     * PasswordReset constructor.
     * @param $user
     * @param $mailToken
     * @param $expiresAt
     */
    public function __construct($user, $mailToken, $expiresAt)
    {
        $this->user = $user;
        $this->mailToken = $mailToken;
        $this->expiresAt = $expiresAt;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.password-reset');
    }
}
