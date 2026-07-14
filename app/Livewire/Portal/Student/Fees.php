<?php

namespace App\Livewire\Portal\Student;

use App\Models\FeeCollection;
use App\Models\Setting;
use App\Models\StudentRegistration;
use Livewire\Component;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class Fees extends Component
{
    protected function student(): ?StudentRegistration
    {
        return StudentRegistration::with('course')->where('user_id', auth()->id())->first();
    }

    protected function razorpayConfigured(): bool
    {
        return (bool) Setting::get('razorpay_enabled', false)
            && Setting::get('razorpay_key_id')
            && Setting::get('razorpay_key_secret');
    }

    public function createOrder($feeCollectionId)
    {
        if (! $this->razorpayConfigured()) {
            session()->flash('error', 'Online payment is not available right now. Please pay at the institute office.');

            return;
        }

        $student = $this->student();

        $fee = FeeCollection::where('id', $feeCollectionId)
            ->where('student_registration_id', $student?->id)
            ->first();

        if (! $fee || $fee->balance <= 0) {
            session()->flash('error', 'This payment is no longer available.');

            return;
        }

        try {
            $api = new Api(Setting::get('razorpay_key_id'), Setting::get('razorpay_key_secret'));

            $order = $api->order->create([
                'receipt' => $fee->receipt_no,
                'amount' => (int) round($fee->balance * 100),
                'currency' => 'INR',
                'notes' => ['fee_collection_id' => $fee->id],
            ]);

            $this->dispatch('razorpay-checkout-open', [
                'key' => Setting::get('razorpay_key_id'),
                'order_id' => $order['id'],
                'amount' => (int) round($fee->balance * 100),
                'name' => Setting::get('institute_name', 'Coaching Institute'),
                'description' => 'Fee Payment — ' . $fee->receipt_no,
                'fee_collection_id' => $fee->id,
            ]);
        } catch (\Throwable $e) {
            session()->flash('error', 'Could not start online payment right now. Please try again later or pay at the office.');
        }
    }

    public function verifyPayment($feeCollectionId, $razorpayPaymentId, $razorpayOrderId, $razorpaySignature)
    {
        if (! $this->razorpayConfigured()) {
            session()->flash('error', 'Online payment is not available right now.');

            return;
        }

        $student = $this->student();

        $fee = FeeCollection::where('id', $feeCollectionId)
            ->where('student_registration_id', $student?->id)
            ->first();

        if (! $fee || $fee->gateway_payment_id) {
            return;
        }

        try {
            $api = new Api(Setting::get('razorpay_key_id'), Setting::get('razorpay_key_secret'));

            $api->utility->verifyPaymentSignature([
                'razorpay_order_id' => $razorpayOrderId,
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_signature' => $razorpaySignature,
            ]);
        } catch (SignatureVerificationError $e) {
            session()->flash('error', 'Payment verification failed. If money was deducted, it will be refunded automatically.');

            return;
        }

        $paidNow = $fee->balance;

        $fee->update([
            'paid_amount' => $fee->paid_amount + $paidNow,
            'balance' => 0,
            'payment_mode' => 'Razorpay',
            'gateway_order_id' => $razorpayOrderId,
            'gateway_payment_id' => $razorpayPaymentId,
        ]);

        $fee->load(['student', 'feeType']);

        foreach (array_filter([$fee->student?->email, $fee->student?->guardian_email]) as $email) {
            \Illuminate\Support\Facades\Notification::route('mail', $email)
                ->notify(new \App\Notifications\FeePaymentReceived($fee));
        }

        session()->flash('success', 'Payment of ₹ ' . number_format($paidNow, 2) . ' received successfully.');
    }

    public function render()
    {
        $student = $this->student();

        $payments = collect();
        $summary = null;

        if ($student) {
            $payments = $student->feeCollections()
                ->with('feeType')
                ->orderByDesc('payment_date')
                ->get();

            $summary = [
                'amount' => $payments->sum('amount'),
                'discount' => $payments->sum('discount'),
                'fine' => $payments->sum('fine'),
                'paid' => $payments->sum('paid_amount'),
                'balance' => $payments->sum('balance'),
            ];
        }

        return view('livewire.portal.student.fees', [
            'student' => $student,
            'payments' => $payments,
            'summary' => $summary,
            'razorpayConfigured' => $this->razorpayConfigured(),
        ])->layout('layouts.admin');
    }
}
