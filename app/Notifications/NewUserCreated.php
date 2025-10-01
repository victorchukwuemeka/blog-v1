<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewUserCreated extends Notification
{
    use Queueable;

    public function __construct(
        public User $user
    ) {}

    public function via(object $notifiable) : array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable) : MailMessage
    {
        return (new MailMessage)
            ->subject('A new user was just created')
            ->line("$this->user->name has just joined the blog.")
            ->action('Check Profile', route('authors.show', $this->user));
    }
}
