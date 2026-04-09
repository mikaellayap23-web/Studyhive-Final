<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $userData;

    public function __construct(array $userData)
    {
        $this->userData = $userData;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Studyhive Account Has Been Created',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.account-created',
        );
    }
}
