<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpdateEmail extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }


    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Update Email Request')
                    ->line('Use The Code Below To Update Your Email')
                    ->line('Code' . ': ' . $this->token)
                    ->line('Thank You For Using' . env('APP_NAME') . '!') ;
                }   

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
