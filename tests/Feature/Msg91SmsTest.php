<?php

use App\Models\Setting;
use App\Models\StudentRegistration;
use App\Notifications\FeeDueReminder;
use App\Notifications\StudentAbsent;
use App\Services\SmsService;
use Illuminate\Support\Facades\Http;

test('sms service does not call the api when sms is disabled', function () {
    Setting::set('sms_enabled', '0');

    Http::fake();

    $sent = app(SmsService::class)->send('9876543210', 'Test message');

    expect($sent)->toBeFalse();

    Http::assertNothingSent();
});

test('sms service does not call the api when msg91 credentials are missing', function () {
    Setting::set('sms_enabled', '1');
    Setting::set('msg91_auth_key', '');
    Setting::set('msg91_sender_id', '');

    Http::fake();

    $sent = app(SmsService::class)->send('9876543210', 'Test message');

    expect($sent)->toBeFalse();

    Http::assertNothingSent();
});

test('sms service calls the msg91 api with the expected payload when configured', function () {
    Setting::set('sms_enabled', '1');
    Setting::set('msg91_auth_key', 'fake-auth-key');
    Setting::set('msg91_sender_id', 'SUNRSE');

    Http::fake([
        'api.msg91.com/*' => Http::response(['type' => 'success'], 200),
    ]);

    $sent = app(SmsService::class)->send('9876543210', 'Your fee is due.');

    expect($sent)->toBeTrue();

    Http::assertSent(function ($request) {
        return $request->url() === 'https://api.msg91.com/api/v2/sendsms'
            && $request->hasHeader('authkey', 'fake-auth-key')
            && $request['sender'] === 'SUNRSE'
            && $request['sms'][0]['to'][0] === '919876543210'
            && $request['sms'][0]['message'] === 'Your fee is due.';
    });
});

test('fee due reminder includes the msg91 channel only when sms is enabled and a mobile exists', function () {
    $studentWithMobile = new StudentRegistration(['mobile' => '9876543210', 'name' => 'Test Student']);

    Setting::set('sms_enabled', '0');
    $notification = new FeeDueReminder($studentWithMobile, 500.0);
    expect($notification->via((object) []))->not->toContain('msg91');

    Setting::set('sms_enabled', '1');
    Setting::set('msg91_auth_key', 'fake-key');
    Setting::set('msg91_sender_id', 'SUNRSE');
    $notification = new FeeDueReminder($studentWithMobile, 500.0);
    expect($notification->via((object) []))->toContain('msg91');

    $payload = $notification->toMsg91((object) []);
    expect($payload['mobile'])->toBe('9876543210')
        ->and($payload['message'])->toContain('Test Student');
});

test('student absent notification includes the msg91 channel only when sms is enabled and a mobile exists', function () {
    $studentWithMobile = new StudentRegistration(['mobile' => '9876543210', 'name' => 'Test Student']);

    Setting::set('sms_enabled', '1');
    Setting::set('msg91_auth_key', 'fake-key');
    Setting::set('msg91_sender_id', 'SUNRSE');

    $notification = new StudentAbsent($studentWithMobile, '2026-07-14');

    expect($notification->via((object) []))->toContain('msg91');

    $payload = $notification->toMsg91((object) []);
    expect($payload['mobile'])->toBe('9876543210')
        ->and($payload['message'])->toContain('ABSENT');
});
