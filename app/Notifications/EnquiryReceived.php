<?php

namespace App\Notifications;

use App\Models\Enquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EnquiryReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Enquiry $enquiry,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Admission Enquiry — ' . $this->enquiry->name)
            ->line('A new enquiry has been submitted from the website:')
            ->line('Name: ' . $this->enquiry->name)
            ->line('Mobile: ' . $this->enquiry->mobile)
            ->line('Email: ' . ($this->enquiry->email ?: '-'))
            ->line('Course: ' . ($this->enquiry->course?->name ?: 'Not specified'))
            ->line('Message: ' . ($this->enquiry->message ?: '-'))
            ->action('View Enquiries', url('/admin/enquiries'));
    }
}
