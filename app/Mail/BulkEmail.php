<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BulkEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subjectLine;

    public $messageBody;

    public $senderName;

    /**
     * Create a new message instance.
     */
    public function __construct(string $subject, string $message, string $senderName)
    {
        $this->subjectLine = $subject;
        $this->messageBody = $message;
        $this->senderName = $senderName;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->subjectLine)
            ->view('emails.bulk')
            ->with([
                'message' => $this->messageBody,
                'senderName' => $this->senderName,
            ]);
    }
}
