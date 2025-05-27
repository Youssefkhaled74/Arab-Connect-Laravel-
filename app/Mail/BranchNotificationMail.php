<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BranchNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $notificationMessage;

    /**
     * Create a new message instance.
     *
     * @param string $notificationMessage
     */
    public function __construct($notificationMessage)
    {
        $this->notificationMessage = $notificationMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Branch Notification')
            ->view('emails.branch_notification')
            ->with('notificationMessage', $this->notificationMessage);
    }
}
