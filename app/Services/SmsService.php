<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function enabled(): bool
    {
        return (bool) Setting::get('sms_enabled', false)
            && Setting::get('msg91_auth_key')
            && Setting::get('msg91_sender_id');
    }

    public function send(string $mobile, string $message): bool
    {
        if (! $this->enabled()) {
            return false;
        }

        $mobile = $this->normalizeMobile($mobile);

        if (! $mobile) {
            return false;
        }

        try {
            $response = Http::withHeaders([
                'authkey' => Setting::get('msg91_auth_key'),
            ])->post('https://api.msg91.com/api/v2/sendsms', [
                'sender' => Setting::get('msg91_sender_id'),
                'route' => '4',
                'country' => '91',
                'sms' => [
                    [
                        'message' => $message,
                        'to' => [$mobile],
                    ],
                ],
            ]);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::warning('MSG91 SMS send failed: ' . $e->getMessage());

            return false;
        }
    }

    protected function normalizeMobile(?string $mobile): ?string
    {
        if (! $mobile) {
            return null;
        }

        $digits = preg_replace('/\D/', '', $mobile);

        if ($digits === null || $digits === '') {
            return null;
        }

        return strlen($digits) === 10 ? '91' . $digits : $digits;
    }
}
