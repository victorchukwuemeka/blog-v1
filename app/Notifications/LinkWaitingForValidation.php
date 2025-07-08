<?php

namespace App\Notifications;

use App\Models\Link;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LinkWaitingForValidation extends Notification
{
    use Queueable;

    public function __construct(public Link $link) {}

    public function via(object $notifiable) : array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable) : MailMessage
    {
        return (new MailMessage)
            ->subject('A link is waiting for validation')
            ->greeting('Heads up!')
            ->line("A link to {$this->link->domain} from {$this->link->user->name} is waiting for validation.")
            ->action('Check', url('/admin/links'));
    }
}
