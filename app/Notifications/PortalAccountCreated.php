<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PortalAccountCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $role,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Coaching Portal Account')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A ' . $this->role . ' portal account has been created for you.')
            ->line('Login email: ' . $notifiable->email)
            ->line('Your default password is your registered mobile number. Please change it after your first login.')
            ->action('Login Now', url('/login'))
            ->line('If you were not expecting this account, please contact the institute.');
    }
}
