<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewReport extends Notification
{
    use Queueable;

    public function __construct(
        public Report $report
    ) {}

    public function via(object $notifiable) : array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable) : MailMessage
    {
        return (new MailMessage)
            ->subject('A new report is available')
            ->line('Your AI-powered editor reviewed one of your posts.')
            ->action('Check Report', route('filament.admin.resources.reports.view', $this->report));
    }
}
