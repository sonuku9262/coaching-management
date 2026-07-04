<?php

namespace App\Notifications;

use App\Models\StudentRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentAbsent extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public StudentRegistration $student,
        public string $date,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Absence Alert — ' . $this->student->name)
            ->greeting('Dear Parent/Student,')
            ->line($this->student->name . ' (' . $this->student->admission_no . ') was marked ABSENT on ' . $this->date . '.')
            ->line('If this is unexpected, please contact the institute office.');
    }
}
