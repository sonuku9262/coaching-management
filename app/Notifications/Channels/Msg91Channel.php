<?php

namespace App\Notifications\Channels;

use App\Services\SmsService;
use Illuminate\Notifications\Notification;

class Msg91Channel
{
    public function __construct(protected SmsService $sms) {}

    public function send($notifiable, Notification $notification)
    {
        if (! method_exists($notification, 'toMsg91')) {
            return;
        }

        $payload = $notification->toMsg91($notifiable);

        if (! $payload || empty($payload['mobile']) || empty($payload['message'])) {
            return;
        }

        $this->sms->send($payload['mobile'], $payload['message']);
    }
}
