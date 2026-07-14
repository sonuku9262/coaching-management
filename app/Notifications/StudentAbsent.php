<?php

namespace App\Notifications;

use App\Models\Setting;
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
        $channels = ['mail'];

        if (app(\App\Services\SmsService::class)->enabled() && $this->student->mobile) {
            $channels[] = 'msg91';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Absence Alert — ' . $this->student->name)
            ->greeting('Dear Parent/Student,')
            ->line($this->student->name . ' (' . $this->student->admission_no . ') was marked ABSENT on ' . $this->date . '.')
            ->line('If this is unexpected, please contact the institute office.');
    }

    public function toMsg91(object $notifiable): ?array
    {
        if (! $this->student->mobile) {
            return null;
        }

        return [
            'mobile' => $this->student->mobile,
            'message' => $this->student->name . ' was marked ABSENT on ' . $this->date . '. Contact the office if unexpected. - '
                . Setting::get('institute_name', 'Institute'),
        ];
    }
}
