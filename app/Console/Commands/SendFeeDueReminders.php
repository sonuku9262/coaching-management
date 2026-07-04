<?php

namespace App\Console\Commands;

use App\Models\StudentRegistration;
use App\Notifications\FeeDueReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendFeeDueReminders extends Command
{
    protected $signature = 'fees:send-reminders';

    protected $description = 'Email a reminder to every active student (and guardian) with an outstanding fee balance';

    public function handle(): int
    {
        $students = StudentRegistration::where('status', 1)
            ->withSum('feeCollections as outstanding_balance', 'balance')
            ->whereRaw('(select coalesce(sum(balance), 0) from fee_collections where fee_collections.student_registration_id = student_registrations.id) > 0')
            ->get();

        $sent = 0;

        foreach ($students as $student) {
            $emails = array_filter([$student->email, $student->guardian_email]);

            if (! $emails) {
                continue;
            }

            foreach ($emails as $email) {
                Notification::route('mail', $email)
                    ->notify(new FeeDueReminder($student, (float) $student->outstanding_balance));
            }

            $sent++;
        }

        $this->info("Fee due reminders queued for {$sent} students.");

        return self::SUCCESS;
    }
}
