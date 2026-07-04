<?php

namespace App\Notifications;

use App\Models\FeeCollection;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FeePaymentReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public FeeCollection $feeCollection,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $fee = $this->feeCollection;

        $mail = (new MailMessage)
            ->subject('Fee Payment Receipt — ' . $fee->receipt_no)
            ->greeting('Dear ' . ($fee->student?->name ?? 'Student') . ',')
            ->line('We have received your fee payment. Details:')
            ->line('Receipt No: ' . $fee->receipt_no)
            ->line('Fee Type: ' . ($fee->feeType?->name ?? '-'))
            ->line('Paid Amount: ₹ ' . number_format((float) $fee->paid_amount, 2))
            ->line('Payment Date: ' . $fee->payment_date);

        if ($fee->balance > 0) {
            $mail->line('Remaining Balance: ₹ ' . number_format((float) $fee->balance, 2));
        }

        return $mail->line('Thank you!');
    }
}
