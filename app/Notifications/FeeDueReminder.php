<?php

namespace App\Notifications;

use App\Models\Setting;
use App\Models\StudentRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FeeDueReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public StudentRegistration $student,
        public float $balance,
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
            ->subject('Fee Due Reminder — ' . $this->student->name)
            ->greeting('Dear Parent/Student,')
            ->line('This is a reminder that a fee balance is pending for ' . $this->student->name . ' (' . $this->student->admission_no . ').')
            ->line('Outstanding Balance: ₹ ' . number_format($this->balance, 2))
            ->line('Please clear the dues at the earliest to avoid any late fine.')
            ->line('If you have already paid, kindly ignore this reminder.');
    }

    public function toMsg91(object $notifiable): ?array
    {
        if (! $this->student->mobile) {
            return null;
        }

        return [
            'mobile' => $this->student->mobile,
            'message' => 'Dear ' . $this->student->name . ', a fee balance of Rs ' . number_format($this->balance, 2)
                . ' is pending. Please clear it at the earliest. - ' . Setting::get('institute_name', 'Institute'),
        ];
    }
}
