<?php

namespace App\Notifications;

use App\Models\JobListing;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class JobListingFetched extends Notification
{
    use Queueable;

    public function __construct(
        public JobListing $jobListing
    ) {}

    public function via(object $notifiable) : array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable) : MailMessage
    {
        return (new MailMessage)
            ->subject('A new job listing was just fetched')
            ->line("A new job listing, \"{$this->jobListing->title}\", was just fetched.")
            ->action('Check Job Listing', route('job-listings.show', $this->jobListing));
    }
}
