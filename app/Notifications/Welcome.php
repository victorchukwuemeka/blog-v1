<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class Welcome extends Notification
{
    use Queueable;

    public function via(object $notifiable) : array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable) : MailMessage
    {
        $mailMessage = (new MailMessage)
            ->subject('Your welcome gifts')
            ->greeting('Thank you for signing up!')
            ->line('You can now post comments or [submit links](' . route('links.index') . ') to content you find useful or wrote.')
            ->line('If you want to keep reading, here are some popular articles:');

        Post::query()
            ->published()
            ->where('sessions_count', '>', 0)
            ->orderBy('sessions_count', 'desc')
            ->inRandomOrder()
            ->limit(5)
            ->get()
            ->each(fn (Post $post) => $mailMessage->line(
                "- [$post->title](" . route('posts.show', $post) . ')'
            ));

        return $mailMessage->line('Are you old school like me? Subscribe to the [Atom feed]().')
            ->line('Find me on [X](https://x.com/benjamincrozat) and [LinkedIn](https://www.linkedin.com/in/benjamincrozat/).');
    }
}
