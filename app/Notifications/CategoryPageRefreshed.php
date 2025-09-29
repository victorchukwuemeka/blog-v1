<?php

namespace App\Notifications;

use App\Models\Category;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CategoryPageRefreshed extends Notification
{
    use Queueable;

    public function __construct(
        public Category $category
    ) {}

    public function via(object $notifiable) : array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable) : MailMessage
    {
        return (new MailMessage)
            ->subject('A category page was just refreshed')
            ->line("Your AI-powered writer revised the content for \"{$this->category->name}\".")
            ->action('Check Category Page', route('filament.admin.resources.categories.edit', $this->category));
    }
}
