<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Symfony\Component\Mime\Email;
use Illuminate\Queue\SerializesModels;

class MailConfirmationController extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user; // will hold the data for the email

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Thank You for Registration')
            ->view('pages.mails.thanksForRegistration')
            ->with([
                'user' => $this->user,
            ]);
    }
}
