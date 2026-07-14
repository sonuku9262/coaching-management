<?php

use App\Models\AcademicSession;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\FeeCollection;
use App\Models\FeeType;
use App\Models\Setting;
use App\Models\Shift;
use App\Models\StudentRegistration;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

function createFeePayableStudent(?int $userId, string $tag): StudentRegistration
{
    $year = AcademicYear::create(['name' => 'RZP-Year-' . $tag, 'start_date' => '2026-04-01', 'end_date' => '2027-03-31', 'status' => true]);
    $session = AcademicSession::create(['academic_year_id' => $year->id, 'name' => 'RZP-Session-' . $tag, 'start_date' => '2026-04-01', 'end_date' => '2027-03-31', 'status' => true]);
    $course = Course::create(['name' => 'RZP Course ' . $tag, 'code' => 'RZP-' . $tag, 'duration' => 1, 'fees' => 10000, 'status' => true]);
    $batch = Batch::create(['academic_year_id' => $year->id, 'academic_session_id' => $session->id, 'course_id' => $course->id, 'name' => 'RZP Batch ' . $tag, 'start_date' => '2026-04-01', 'end_date' => '2027-03-31', 'capacity' => 30, 'status' => true]);
    $classroom = Classroom::create(['name' => 'RZP Room ' . $tag, 'room_no' => 'RZP-' . $tag, 'capacity' => 30, 'status' => true]);
    $shift = Shift::create(['name' => 'RZP Shift ' . $tag, 'start_time' => '08:00', 'end_time' => '12:00', 'status' => true]);

    return StudentRegistration::create([
        'user_id' => $userId,
        'admission_no' => 'ADM-RZP-' . $tag,
        'academic_year_id' => $year->id,
        'academic_session_id' => $session->id,
        'course_id' => $course->id,
        'batch_id' => $batch->id,
        'classroom_id' => $classroom->id,
        'shift_id' => $shift->id,
        'name' => 'RZP Student ' . $tag,
        'father_name' => 'Father',
        'gender' => 'Male',
        'dob' => '2010-01-01',
        'mobile' => '9999999999',
        'address' => 'Address',
        'admission_date' => '2026-04-01',
        'status' => true,
    ]);
}

function razorpaySignatureFor(string $orderId, string $paymentId, string $secret): string
{
    return hash_hmac('sha256', $orderId . '|' . $paymentId, $secret);
}

test('the pay online button is hidden when razorpay is not configured', function () {
    $user = User::factory()->create();
    $user->assignRole('student');
    createFeePayableStudent($user->id, 'HID');

    $this->actingAs($user)->get('/student/fees')->assertOk()->assertDontSee('Pay Online');
});

test('the pay online button appears once razorpay is configured', function () {
    Setting::set('razorpay_enabled', '1');
    Setting::set('razorpay_key_id', 'rzp_test_fakekey');
    Setting::set('razorpay_key_secret', 'fakesecret');

    $user = User::factory()->create();
    $user->assignRole('student');
    $student = createFeePayableStudent($user->id, 'SHOW');

    $feeType = FeeType::create(['name' => 'RZP Tuition', 'code' => 'RZPTUT', 'status' => true]);

    FeeCollection::create([
        'student_registration_id' => $student->id,
        'fee_type_id' => $feeType->id,
        'amount' => 5000,
        'discount' => 0,
        'fine' => 0,
        'paid_amount' => 0,
        'balance' => 5000,
        'payment_mode' => 'Cash',
        'receipt_no' => 'RCPT-RZP-SHOW',
        'payment_date' => now()->format('Y-m-d'),
        'status' => true,
    ]);

    $this->actingAs($user)->get('/student/fees')->assertOk()->assertSee('Pay Online');
});

test('a valid razorpay signature marks the fee as fully paid', function () {
    Setting::set('razorpay_enabled', '1');
    Setting::set('razorpay_key_id', 'rzp_test_fakekey');
    Setting::set('razorpay_key_secret', 'fakesecret');

    $user = User::factory()->create();
    $user->assignRole('student');
    $student = createFeePayableStudent($user->id, 'PAY');

    $feeType = FeeType::create(['name' => 'RZP Tuition Pay', 'code' => 'RZPTUTPAY', 'status' => true]);

    $fee = FeeCollection::create([
        'student_registration_id' => $student->id,
        'fee_type_id' => $feeType->id,
        'amount' => 4000,
        'discount' => 0,
        'fine' => 0,
        'paid_amount' => 0,
        'balance' => 4000,
        'payment_mode' => 'Cash',
        'receipt_no' => 'RCPT-RZP-PAY',
        'payment_date' => now()->format('Y-m-d'),
        'status' => true,
    ]);

    $orderId = 'order_fake123';
    $paymentId = 'pay_fake456';
    $signature = razorpaySignatureFor($orderId, $paymentId, 'fakesecret');

    Livewire::actingAs($user)
        ->test(\App\Livewire\Portal\Student\Fees::class)
        ->call('verifyPayment', $fee->id, $paymentId, $orderId, $signature);

    $fee->refresh();

    expect((float) $fee->balance)->toBe(0.0)
        ->and((float) $fee->paid_amount)->toBe(4000.0)
        ->and($fee->payment_mode)->toBe('Razorpay')
        ->and($fee->gateway_payment_id)->toBe($paymentId);
});

test('a tampered razorpay signature is rejected and the fee stays unpaid', function () {
    Setting::set('razorpay_enabled', '1');
    Setting::set('razorpay_key_id', 'rzp_test_fakekey');
    Setting::set('razorpay_key_secret', 'fakesecret');

    $user = User::factory()->create();
    $user->assignRole('student');
    $student = createFeePayableStudent($user->id, 'TAMPER');

    $feeType = FeeType::create(['name' => 'RZP Tuition Tamper', 'code' => 'RZPTUTTAMP', 'status' => true]);

    $fee = FeeCollection::create([
        'student_registration_id' => $student->id,
        'fee_type_id' => $feeType->id,
        'amount' => 4000,
        'discount' => 0,
        'fine' => 0,
        'paid_amount' => 0,
        'balance' => 4000,
        'payment_mode' => 'Cash',
        'receipt_no' => 'RCPT-RZP-TAMPER',
        'payment_date' => now()->format('Y-m-d'),
        'status' => true,
    ]);

    Livewire::actingAs($user)
        ->test(\App\Livewire\Portal\Student\Fees::class)
        ->call('verifyPayment', $fee->id, 'pay_fake456', 'order_fake123', 'not-a-real-signature');

    $fee->refresh();

    expect((float) $fee->balance)->toBe(4000.0)
        ->and($fee->gateway_payment_id)->toBeNull();
});

test('a student cannot verify payment against another students fee row', function () {
    Setting::set('razorpay_enabled', '1');
    Setting::set('razorpay_key_id', 'rzp_test_fakekey');
    Setting::set('razorpay_key_secret', 'fakesecret');

    $me = User::factory()->create();
    $me->assignRole('student');
    createFeePayableStudent($me->id, 'ME');

    $otherUser = User::factory()->create();
    $otherStudent = createFeePayableStudent($otherUser->id, 'OTHER');

    $feeType = FeeType::create(['name' => 'RZP Other Tuition', 'code' => 'RZPOTH', 'status' => true]);

    $otherFee = FeeCollection::create([
        'student_registration_id' => $otherStudent->id,
        'fee_type_id' => $feeType->id,
        'amount' => 4000,
        'discount' => 0,
        'fine' => 0,
        'paid_amount' => 0,
        'balance' => 4000,
        'payment_mode' => 'Cash',
        'receipt_no' => 'RCPT-RZP-OTHER',
        'payment_date' => now()->format('Y-m-d'),
        'status' => true,
    ]);

    $orderId = 'order_fakeXYZ';
    $paymentId = 'pay_fakeXYZ';
    $signature = razorpaySignatureFor($orderId, $paymentId, 'fakesecret');

    Livewire::actingAs($me)
        ->test(\App\Livewire\Portal\Student\Fees::class)
        ->call('verifyPayment', $otherFee->id, $paymentId, $orderId, $signature);

    $otherFee->refresh();

    expect((float) $otherFee->balance)->toBe(4000.0)
        ->and($otherFee->gateway_payment_id)->toBeNull();
});
